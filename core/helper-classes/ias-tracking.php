<?php
	/**
	 * IAS Tracking Class
	 * Used for reporting to various systems on certain "triggers"
	 */
	class ias_tracking {
		public $postback_urls = array();
		public $image_pixels = array();
		public $iframe_pixels = array();
		public $js = array();
		
		function __construct( $trigger = 'visit' ) {
			global $wpdb;
			$pixels = $wpdb->get_results( ias_fix_db_prefix( "SELECT * FROM `{{ias}}postbacks` WHERE `trigger` = '" . $trigger . "'" ), ARRAY_A );
			if( $wpdb->num_rows > 0 ) {
				foreach ( $pixels as $pixel ) {
					switch ($pixel['type']) {
						case 'server':
							if( strpos($pixel['content'], 'http') !== FALSE ) {
								array_push( $this->postback_urls, $this->switch_out_macros( $pixel['content'] ) );
							}
							break;
						case 'image':
							$html = str_get_html( $pixel['content'] );
							$elemts = $html->find('img');
							if( count( $elemts ) != 1 ) {
								# do nothing
							}
							else if( !isset( $elemts[0]->src ) || strlen( $elemts[0]->src ) == 0 ) {
								# do nothing
							}
							else if ( !filter_var( $elemts[0]->src , FILTER_VALIDATE_URL , FILTER_FLAG_HOST_REQUIRED ) ) {
								# do nothing
							}
							$elemts[0]->src = $this->switch_out_macros( $elemts[0]->src );
							$elemts[0]->style = 'display:none !important;';
							array_push( $this->image_pixels , $html->save() );
							break;
						case 'iframe':
							$html = str_get_html( $pixel['content'] );
							$elemts = $html->find('iframe');
							if( count( $elemts ) != 1 ) {
								# do nothing
							}
							else if( !isset( $elemts[0]->src ) || strlen( $elemts[0]->src ) == 0 ) {
								# do nothing
							}
							else if ( !filter_var( $elemts[0]->src , FILTER_VALIDATE_URL , FILTER_FLAG_HOST_REQUIRED ) ) {
								# do nothing
							}
							$elemts[0]->src = $this->switch_out_macros( $elemts[0]->src );
							$elemts[0]->style = 'display:none !important;';
							array_push( $this->iframe_pixels , $html->save() );
							break;
						case 'js':
							$html = str_get_html( $pixel['content'] );
							$elemts = $html->find('script');
							if( count( $elemts ) != 1 ) {
								# do nothing
							}
							array_push( $this->js , $this->switch_out_macros( $html->save() ) ) ;
							break;
					}
				}
			}
		}
		private function switch_out_macros( $input ) {
			$mapped = array(
				'ip' => $_SESSION['ias_geoip']->ip,
				'iso' => $_SESSION['ias_geoip']->iso,
				'spotid' => $_SESSION['ias_geoip']->spotid,
				'ias_region' => $_SESSION['ias_geoip']->region,
				'country_prefix' => $_SESSION['ias_geoip']->prefix,
				'location_country' => $_SESSION['ias_geoip']->countryName,
			);
			if( isset($_SESSION['ias_geoip']->omni) ) {
				foreach ($_SESSION['ias_geoip']->omni as $key => $value) {
					$mapped['location_' . $key] = $value;
				}
			}
			if(isset($_SESSION['ias_customer'])) {
				foreach ( $_SESSION['ias_customer'] as $key => $value) {
					$mapped['customer_' . $key] = $value;
				}
			}
			else if( isset( $_GET['postback'] ) && isset( $_GET['customer_id'] ) && isset( $_GET['broker_id'] ) ) {
				$customer = new ias_customer( $_GET['broker_id'], $_GET['customer_id']);
				foreach ( $customer as $key => $value) {
					$mapped['customer_' . $key] = $value;
				}
			}
			foreach ( $_SESSION['ias_tracking'] as $key => $value) {
				$mapped[$key] = $value;
			}
			$output = $input;
			foreach ($mapped as $key => $value) {
				$output = str_replace('{'. $key . '}', $value, $output);
			}
			return $output;
		}
		public static function do_server_postbacks( $trigger = 'visit' ) {
			$class = __CLASS__;
			$obj = new $class( $trigger );
			$reactor = (new Alert\ReactorFactory)->select();
			$client = new Artax\AsyncClient($reactor);
			$requests = $obj->postback_urls;
			if( count($requests) > 0 ) {
				$unfinishedRequests = count($requests);
				$onResponse = function(Artax\Response $response, Artax\Request $request) use (&$unfinishedRequests, $reactor) {
				    if( $response->getStatus() !== 200 ) {
				    	$bug_report = 'There was an issue with the server postback url ' . $request->getUri() . '.' . "\r\n" . "\r\n";
				    	$bug_report .= '*Return from Attempt*' . "\r\n" . "\r\n";
				    	$bug_report .= '<pre>' . "\r\n";
				    	$bug_report .= print_r( $response , true ) . "\r\n";
				    	$bug_report .= '</pre>' . "\r\n";
				    	report_ias_bug( 'Pixel Fire Failure from ' . get_bloginfo('wpurl') , $bug_report );
				    }
				    /**
				     * Left here in case someone needs to debug!
				     */
				    //else {
				    //	$bug_report = 'Response from Pixel ' . $request->getUri() . '.' . "\r\n" . "\r\n";
				    //	$bug_report .= '*Return from Attempt*' . "\r\n" . "\r\n";
				    //	$bug_report .= '<pre>' . "\r\n";
				    //	$bug_report .= print_r( $response , true ) . "\r\n";
				    //	$bug_report .= '</pre>' . "\r\n";
				    //	report_ias_bug( 'Response from Pixel ' . get_bloginfo('wpurl') , $bug_report );
				    //}
				    /**
				     * Report Back to Pixel Log
				     */
				    do_action( 'finishedServerPostback' , $response );
				    if (!--$unfinishedRequests) {
				        $reactor->stop();
				    }
				};
				$onError = function(Exception $e, Artax\Request $request) use (&$unfinishedRequests, $reactor) {
				    $bug_report = 'An exception occured when trying to post back to url ' . $request->getUri() . '.' . "\r\n" . "\r\n";
				    $bug_report .= '*Exception*' . "\r\n" . "\r\n";
				    $bug_report .= '<pre>' . "\r\n";
				    $bug_report .= print_r( $e->getMessage() , true ) . "\r\n";
				    $bug_report .= '</pre>' . "\r\n";
				    report_ias_bug( 'Pixel Fire Failure from ' . get_bloginfo('wpurl') , $bug_report );
				    if (!--$unfinishedRequests) {
				        $reactor->stop();
				    }
				};
				$reactor->immediately(function() use ($client, $requests, $onResponse, $onError) {
				    foreach ($requests as $uri) {
				        $client->request($uri, $onResponse, $onError);
				    }
				});
				$reactor->run();
			}
		}
		public static function do_client_side_pixels( $content ) {
			if(isset( $_SESSION['report'] ) ) {
				$class = __CLASS__;
				$obj = new $class( $_SESSION['report'] );
				$tracking = '';
				foreach ($obj->image_pixels as $pixel) {
					$tracking .= $pixel . "\r\n";
				}
				foreach ($obj->iframe_pixels as $pixel) {
					$tracking .= $pixel . "\r\n";
				}
				foreach ($obj->js as $pixel) {
					$tracking .= $pixel . "\r\n";
				}
				$content = $tracking . $content;
				unset($_SESSION['report']);
			}
			$class = __CLASS__;
			$visit = new $class();
			$tracking = '';
			foreach ($visit->image_pixels as $pixel) {
				$tracking .= $pixel . "\r\n";
			}
			foreach ($visit->iframe_pixels as $pixel) {
				$tracking .= $pixel . "\r\n";
			}
			foreach ($visit->js as $pixel) {
				$tracking .= $pixel . "\r\n";
			}
			$content = $tracking . $content;
			return $content;
		}

		public static function tracking_trigger( $trigger , $doPostback = TRUE ) {
			if( $doPostback == TRUE ) {
				self::do_server_postbacks( $trigger );
			}
			$_SESSION['report'] = $trigger;
			if( $trigger == 'deposit' ) {
				ias_customer::reload_customer_information();
			}
		}
	} // end of ias_tracking class
?>