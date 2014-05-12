<?php
	function ias_init() {
		global $ias_session;
		do_action('ias_main_init');
		do_action('ias_visit',$_GET);
		ias_session_start();
		ias_activate_geoip();
		ias_activate_tracking();
		ias_perma_get();
		$last_ias_update = get_site_option( 'ias_last_update' , 0 );
		if( time() - $last_ias_update > 3600 || get_site_option( 'ias_update_available' , FALSE ) == TRUE ) {
			ias_updates();
		}
		$ias_session = $_SESSION;
		remove_filter( 'the_content', 'wpautop' );
		form_actions();
		show_phone_lib();
		show_brands_lib();
		broker_postback();
		pending_postbacks();
		ias_tracking::do_server_postbacks('visit');
	}

	function ias_filter_plugin_meta( $plugin_meta, $plugin_file, $plugin_data = NULL, $status = NULL ) {
		if( strpos( $plugin_file , IAS_PLUGIN_BASENAME ) !== FALSE ) {
			$plugin_meta[] = __('Version: ',IAS_TEXTDOMAIN) . '<a href="https://rm.14all.me/versions/' . IAS_VERSION_ID . '" target="_blank">' . ' ' . IAS_VERSION . '</a>';
			$plugin_meta[] = '<a href="admin.php?page=ias">' . __('Settings',IAS_TEXTDOMAIN) . '</a>';
			$plugin_meta[] = '<a href="https://rm.14all.me/projects/ias/wiki/How_to_Use" target="_blank">' . __('How to Use',IAS_TEXTDOMAIN) . '</a>';
		}
		return $plugin_meta;
	}

	function ias_session_start() {
		if( !session_id() ) {
			session_start();
		}
		if(!isset($_SESSION['ip']) && !isset($_SESSION['HTTP_USER_AGENT'])) {
			$_SESSION['ip'] = ip();
			$_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
			$_SESSION['reset'] = NULL;
		} else if( $_SESSION['ip'] !== ip() || $_SESSION['HTTP_USER_AGENT'] !== $_SERVER['HTTP_USER_AGENT'] ) {
			session_destroy();
			session_start();
			$_SESSION['ip'] = ip();
			$_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
			$_SESSION['reset'] = TRUE;
		} else {
			$_SESSION['reset'] = FALSE;
		}
	}

	function ias_activate_geoip() {
		if(!isset($_SESSION['ias_geoip']) || !is_object($_SESSION['ias_geoip']) ) {
			$_SESSION['ias_geoip'] = new ias_geoip();
		}
	}

	function ias_activate_tracking() {
		$_SESSION['ias_tracking'] = new ias_affiliate_tracking();
	}

	function ias_perma_get() {
		if(!isset($_SESSION['perma_get'])) {
			$_SESSION['perma_get'] = array();
		}
		$banned_get = array(
			'page',
			'post',
			'action',
			'message',
			'a_aid',
			'a_bid',
			'a_cid',
			'tracker',
		);
		foreach ($_GET as $key => $value) {
			if(!in_array($key, $banned_get)) {
				$_SESSION['perma_get'][$key] = $value;
			}
		}
	}

	function cleanup_shortcode_fix($content) {
	    $array = array('<p>[' => '[', ']</p>' => ']', ']<br />' => ']', ']<br>' => ']');
	    $content = strtr($content, $array);
	    return $content;
	}

	function form_actions() {
		if(!isset($_POST['action']) && !isset($_POST['form_id'])) {
			return FALSE;
		}
		if( isset($_POST['form_id']) ) {
			$id = $_POST['form_id'];
			$nonce = $_POST[ 'form_' . $id . '_nonce' ];
			$nonce_response = wp_verify_nonce( $nonce );
			$actions_array = array(
				'login' => array( 'ias_login_form' , 'action' ),
				'logout' => array( 'ias_login_form' , 'logout' ),
				'registerCustomer' => array( 'ias_registration_form' , 'action'),
				'makeDeposit' => array( 'ias_deposit_form' , 'action'),
			);
			if( isset( $actions_array[$_POST['action']] ) ) {
				$action = $actions_array[$_POST['action']];
				if( is_array($action) ) {
					$class = $action[0];
					$method = $action[1];
					$class::{$method}();
				} else {
					$action();
				}
			}
		}
	}

	function show_client_errors( $content ) {
		global $ias_session;
		if(isset($_SESSION['client_errors']) && is_array($_SESSION['client_errors'])) {
			$html = '';
			foreach ($_SESSION['client_errors'] as $error) {
				$html .= '<div class="alert alert-danger">' . "\r\n";
				$html .= '	' . __( $error , IAS_TEXTDOMAIN ) . "\r\n";
				$html .= '</div>' . "\r\n";
			}
			unset($_SESSION['client_errors']);
			unset($ias_session['client_errors']);
			return $html . $content;
		} else {
			return $content;
		}
	}

	function push_client_error( $error ) {
		if(!isset($_SESSION['client_errors'])) {
			$_SESSION['client_errors'] = array();
		}
		array_push( $_SESSION['client_errors'] , $error );
	}

	function show_phone_lib() {
		if(isset($_GET['phonelib'])) {
			header('Content-type: application/javascript');
			require_once( IAS_BASE . '/clientLibs/lib-phonenumber.js' );
			exit();
		}
	}

	function show_brands_lib() {
		if(isset($_GET['brandlib'])) {
			header('Content-type: application/javascript');
			require_once( IAS_BASE . '/clientLibs/lib-brands.js' );
			exit();
		}
	}

	function broker_postback() {
		global $wpdb;
		if( isset( $_GET['postback'] ) ) {
			header('Content-type: text/json;X-Content-Type-Options: nosniff; charset=UTF-8;');
			if( !isset( $_GET['customer_id'] ) || !isset( $_GET['broker_id'] ) ) {
				$return_array = array(
					'error' => __('Missing Critical Information' , IAS_TEXTDOMAIN ),
				);
				print( json_encode( $return_array ) );
				$rm_error = 'An attempted firing of a server postback pixel failed with the following information: ' . "\r\n";
				$rm_info = array(
						'attempt_url' => $_SERVER['SCRIPT_URI'],
						'attemp_query' => $_SERVER['QUERY_STRING'],
						'attempt_method' => $_SERVER['REQUEST_METHOD'],
						'remote_addr' => $_SERVER['REMOTE_ADDR'],
						'resolved_addr' => ip(),
					);
				$rm_error .= '<pre>' . "\r\n";
				$rm_error .= print_r( $rm_info , TRUE ) . "\r\n";
				$rm_error .= '</pre>' . "\r\n";
				report_ias_bug( 'Postback Error on site ' . get_bloginfo('wpurl') , $rm_error );
				exit();
			}
			switch ( $_GET['postback'] ) {
				case 'deposit':
					ias_tracking::do_server_postbacks('deposit');
					$wpdb->insert( ias_fix_db_prefix('{{ias}}pending_postbacks') , array(
							'customer_id' => $_GET['customer_id'],
							'brand_id' => $_GET['broker_id'],
							'trigger' => 'deposit',
							'fired' => FALSE,
						));
					$return_array = array(
						'success' => TRUE,
						'id' => $wpdb->insert_id,
					);
					print( json_encode($return_array) );
					break;

				default:
					$return_array = array(
						'error' => __('Invalid Conversion Type' , IAS_TEXTDOMAIN ),
					);
					print( json_encode( $return_array ) );
					$rm_error = 'An attempted firing of a server postback pixel failed with the following information: ' . "\r\n";
					$rm_info = array(
							'attempt_url' => $_SERVER['SCRIPT_URI'],
							'attemp_query' => $_SERVER['QUERY_STRING'],
							'attempt_method' => $_SERVER['REQUEST_METHOD'],
							'remote_addr' => $_SERVER['REMOTE_ADDR'],
							'resolved_addr' => ip(),
						);
					$rm_error .= '<pre>' . "\r\n";
					$rm_error .= print_r( $rm_info , TRUE ) . "\r\n";
					$rm_error .= '</pre>' . "\r\n";
					report_ias_bug( 'Postback Error on site ' . get_bloginfo('wpurl') , $rm_error );
					break;
			}
			exit();
		}
		else {
			// check 3rd party postbacks
		}
	}

	function pending_postbacks() {
		global $wpdb;
		if( isset( $_SESSION['ias_customer'] ) && isset( $_SESSION['ias_customer']->valid ) && $_SESSION['ias_customer']->valid == TRUE ) {
			$triggers = $wpdb->get_results( ias_fix_db_prefix("SELECT * FROM `{{ias}}pending_postbacks` WHERE `fired` = 0 AND `customer_id` = '" . $_SESSION['ias_customer']->id . "' AND `brand_id` = '" . $_SESSION['ias_customer']->brand_id . "'") , ARRAY_A );
			if( count($triggers) > 0 ) {
				foreach ($triggers as $trigger) {
					ias_tracking::tracking_trigger( $trigger['trigger'] , FALSE );
					$wpdb->update( ias_fix_db_prefix('{{ias}}pending_postbacks') , array('fired' => 1 ) , array( 'id' => $trigger['id'] ) );
				}
			}
		}
	}

	/**
	 * Add some filters
	 */
	add_filter('the_content', 'ias_check_if_login_required');
	add_filter('the_content', 'cleanup_shortcode_fix');
	add_filter('the_content', 'show_client_errors');
	add_filter('the_content', array( 'ias_tracking' , 'do_client_side_pixels') );
?>