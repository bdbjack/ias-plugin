<?php
	/**
	 * Class used to capture affiliate tracking variables and ensure that all actions are captured correctly.
	 */
	class ias_affiliate_tracking {
		public $a_aid = NULL;
		public $a_bid = NULL;
		public $a_cid = NULL;
		public $tracker = NULL;

		function __construct() {
			global $wpdb;
			$overridetype = get_site_option('tracking_ias_override_type','lcw');
			$expiry = get_site_option('tracking_ias_time', ( 60 * 60 * 24 * 365 * 5 ) + 1 );
			$db_tracking_info = $wpdb->get_results( ias_fix_db_prefix("SELECT * FROM `{{ias}}tracking` WHERE `ip` = '" . $_SESSION['ip'] . "' AND `agent` = '" . base64_encode($_SESSION['HTTP_USER_AGENT']) . "' ORDER BY `time` DESC"), ARRAY_A);
			if( count($db_tracking_info) == 0 ) {
				if( isset($_REQUEST['a_aid']) || isset($_REQUEST['a_bid']) || isset($_REQUEST['a_cid']) || isset($_REQUEST['tracker']) ) {
					if(!isset($_REQUEST['a_aid'])) {
						$_REQUEST['a_aid'] = NULL;
					}
					if(!isset($_REQUEST['a_bid'])) {
						$_REQUEST['a_bid'] = NULL;
					}
					if(!isset($_REQUEST['a_cid'])) {
						$_REQUEST['a_cid'] = NULL;
					}
					if(!isset($_REQUEST['tracker'])) {
						$_REQUEST['tracker'] = NULL;
					}
					$insert_info = array(
						'ip' => $_SESSION['ip'],
						'agent' => base64_encode($_SESSION['HTTP_USER_AGENT']),
						'time' => time(),
						'a_aid' => $_REQUEST['a_aid'],
						'a_bid' => $_REQUEST['a_bid'],
						'a_cid' => $_REQUEST['a_cid'],
						'tracker' => $_REQUEST['tracker'],
					);
					$wpdb->insert( ias_fix_db_prefix('{{ias}}tracking') , $insert_info );
					$this->a_aid = $_REQUEST['a_aid'];
					$this->a_bid = $_REQUEST['a_bid'];
					$this->a_cid = $_REQUEST['a_cid'];
					$this->tracker = $_REQUEST['tracker'];
					$this->set_cookies();
				}
			} 
			else {
				/**
				 * Now it's switch time according to the policies & what information is set!
				 */
				# if cookies are never meant to expire and the over-ride is first cookie wins
				if( $expiry == ( ( 60 * 60 * 24 * 365 * 5 ) + 1 ) && $overridetype == 'fcw' ) {
					$info = $db_tracking_info[0];
					$this->a_aid = $info['a_aid'];
					$this->a_bid = $info['a_bid'];
					$this->a_cid = $info['a_cid'];
					$this->tracker = $info['tracker'];
					$this->set_cookies();
				}
				# if cookies are never meant to expire and the over-ride is the last cookie wins
				elseif( $expiry == ( ( 60 * 60 * 24 * 365 * 5 ) + 1 ) && $overridetype == 'lcw' ) {
					# if we have new information
					if( isset($_REQUEST['a_aid']) || isset($_REQUEST['a_bid']) || isset($_REQUEST['a_cid']) || isset($_REQUEST['tracker']) ) {
						if(!isset($_REQUEST['a_aid'])) {
							$_REQUEST['a_aid'] = NULL;
						}
						if(!isset($_REQUEST['a_bid'])) {
							$_REQUEST['a_bid'] = NULL;
						}
						if(!isset($_REQUEST['a_cid'])) {
							$_REQUEST['a_cid'] = NULL;
						}
						if(!isset($_REQUEST['tracker'])) {
							$_REQUEST['tracker'] = NULL;
						}
						$insert_info = array(
							'ip' => $_SESSION['ip'],
							'agent' => base64_encode($_SESSION['HTTP_USER_AGENT']),
							'time' => time(),
							'a_aid' => $_REQUEST['a_aid'],
							'a_bid' => $_REQUEST['a_bid'],
							'a_cid' => $_REQUEST['a_cid'],
							'tracker' => $_REQUEST['tracker'],
						);
						$wpdb->update( ias_fix_db_prefix('{{ias}}tracking') , $insert_info , array( 'ip' => $_SESSION['ip'] , 'agent' => base64_encode($_SESSION['HTTP_USER_AGENT'])) );
						// Update the object & cookies according to what's in the database now!
						$this->a_aid = $_REQUEST['a_aid'];
						$this->a_bid = $_REQUEST['a_bid'];
						$this->a_cid = $_REQUEST['a_cid'];
						$this->tracker = $_REQUEST['tracker'];
						$this->set_cookies();
					}
					# if we don't have new information
					else {
						$info = $db_tracking_info[0];
						$this->a_aid = $info['a_aid'];
						$this->a_bid = $info['a_bid'];
						$this->a_cid = $info['a_cid'];
						$this->tracker = $info['tracker'];
						$this->set_cookies();
					}
				}
				# if cookies expire and the over-ride is that last cookie wins
				elseif( $expiry != ( ( 60 * 60 * 24 * 365 * 5 ) + 1 ) && $overridetype == 'lcw' ) {
					#if we are past our expiry time
					if( time() > $expiry + $db_tracking_info[0]['time'] ) {
						# if we have new information
						if( isset($_REQUEST['a_aid']) || isset($_REQUEST['a_bid']) || isset($_REQUEST['a_cid']) || isset($_REQUEST['tracker']) ) {
							if(!isset($_REQUEST['a_aid'])) {
								$_REQUEST['a_aid'] = NULL;
							}
							if(!isset($_REQUEST['a_bid'])) {
								$_REQUEST['a_bid'] = NULL;
							}
							if(!isset($_REQUEST['a_cid'])) {
								$_REQUEST['a_cid'] = NULL;
							}
							if(!isset($_REQUEST['tracker'])) {
								$_REQUEST['tracker'] = NULL;
							}
							$insert_info = array(
								'ip' => $_SESSION['ip'],
								'agent' => base64_encode($_SESSION['HTTP_USER_AGENT']),
								'time' => time(),
								'a_aid' => $_REQUEST['a_aid'],
								'a_bid' => $_REQUEST['a_bid'],
								'a_cid' => $_REQUEST['a_cid'],
								'tracker' => $_REQUEST['tracker'],
							);
							$wpdb->update( ias_fix_db_prefix('{{ias}}tracking') , $insert_info , array( 'ip' => $_SESSION['ip'] , 'agent' => base64_encode($_SESSION['HTTP_USER_AGENT'])) );
							// Update the object & cookies according to what's in the database now!
							$this->a_aid = $_REQUEST['a_aid'];
							$this->a_bid = $_REQUEST['a_bid'];
							$this->a_cid = $_REQUEST['a_cid'];
							$this->tracker = $_REQUEST['tracker'];
							$this->set_cookies();
						}
						# if we don't have new information
						else {
							$this->a_aid = NULL;
							$this->a_bid = NULL;
							$this->a_cid = NULL;
							$this->tracker = NULL;
							$this->set_cookies();
						}
					} 
					# information hasn't expired, so we'll go by what's in the database
					else {
						$info = $db_tracking_info[0];
						$this->a_aid = $info['a_aid'];
						$this->a_bid = $info['a_bid'];
						$this->a_cid = $info['a_cid'];
						$this->tracker = $info['tracker'];
						$this->set_cookies();
					}
				}
				# first cookie wins policy
				else {
					$info = $db_tracking_info[0];
					$this->a_aid = $info['a_aid'];
					$this->a_bid = $info['a_bid'];
					$this->a_cid = $info['a_cid'];
					$this->tracker = $info['tracker'];
					$this->set_cookies();
				}
			}
		}

		private function set_cookies() {
			foreach ($this as $key => $value) {
				setcookie( $key , '' , time() - get_site_option('tracking_ias_time', ( 60 * 60 * 24 * 365 * 5 ) + 1 ) , COOKIEPATH , COOKIE_DOMAIN , FALSE , TRUE );
				setcookie( $key , $value , time() + get_site_option('tracking_ias_time', ( 60 * 60 * 24 * 365 * 5 ) + 1 ) , COOKIEPATH , COOKIE_DOMAIN , FALSE , TRUE );
			}
		}
	} // end ias_affiliate_tracking class
?>