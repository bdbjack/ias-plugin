<?php
	class ias_cc_fraud_api {
		public $i = NULL;
		public $city = NULL;
		public $region = NULL;
		public $postal = NULL;
		public $country = NULL;
		public $license_key = 'XTz2Lfu6u3M1'; // Courtesy of Banc De Binary
		public $bin = NULL;
		public $emailDomain = NULL;
		public $custPhone = NULL;
		public $emailMD5 = NULL;
		public $usernameMD5 = NULL;
		public $passwordMD5 = NULL;
		public $sessionId = NULL;
		public $user_agent = NULL;
		public $accept_language = NULL;
		public $txnID = NULL;
		public $order_amount = NULL;
		public $order_currency = NULL;
		public $txn_type = 'creditcard';

		function __construct( $atts = array() ) {
			foreach ($atts as $key => $value) {
				$this->$key = $value;
			}
			$this->i = $_SESSION['ip'];
			if( is_null( $this->city ) ) {
				$this->city = $_SESSION['ias_geoip']->omni['city_name'];
			}
			if( is_null( $this->region ) ) {
				$this->region = $_SESSION['ias_geoip']->omni['region_name'];
			}
			if( is_null( $this->postal ) ) {
				$this->postal = $_SESSION['ias_geoip']->omni['postal_code'];
			}
			if( is_null( $this->country ) ) {
				$this->country = $_SESSION['ias_geoip']->omni['country_code'];
			}
			if( is_null( $this->emailDomain ) ) {
				if( isset( $_SESSION['ias_customer'] ) && isset( $_SESSION['ias_customer']->valid ) && $_SESSION['ias_customer']->valid == TRUE ) {
					$atsignpos = strpos($_SESSION['ias_customer']->email , '@' );
					$domain = substr($_SESSION['ias_customer']->email, $atsignpos );
					$domain = str_replace('@', '', $domain);
					$this->emailDomain = $domain;
					$this->emailMD5 = md5($_SESSION['ias_customer']->email);
				}
				else if ( isset( $_SESSION['perma_get']['email'] ) ) {
					$atsignpos = strpos($_SESSION['perma_get']['email'] , '@' );
					$domain = substr($_SESSION['perma_get']['email'], $atsignpos );
					$domain = str_replace('@', '', $domain);
					$this->emailDomain = $domain;
					$this->emailMD5 = md5($_SESSION['perma_get']['email']);
				}
				else if ( isset( $_POST['email'] ) ) {
					$atsignpos = strpos($_POST['email'] , '@' );
					$domain = substr($_POST['email'], $atsignpos );
					$domain = str_replace('@', '', $domain);
					$this->emailDomain = $domain;
					$this->emailMD5 = md5($_POST['email']);
				}
			}
			if( is_null ( $this->sessionId ) ) {
				$this->sessionId = ( !session_id() ) ? NULL : session_id();
			}
			if( is_null( $this->custPhone ) ) {
				if( isset( $_SESSION['ias_customer'] ) && isset( $_SESSION['ias_customer']->valid ) && $_SESSION['ias_customer']->valid == TRUE ) {
					$this->custPhone = $_SESSION['ias_customer']->phone;
				}
			}
			if( is_null( $this->usernameMD5 ) ) {
				if( isset( $_SESSION['ias_customer'] ) && isset( $_SESSION['ias_customer']->valid ) && $_SESSION['ias_customer']->valid == TRUE ) {
					$this->usernameMD5 = md5( $_SESSION['ias_customer']->id );
				}
			}
			if( is_null( $this->passwordMD5 ) ) {
				if( isset( $_SESSION['ias_customer'] ) && isset( $_SESSION['ias_customer']->valid ) && $_SESSION['ias_customer']->valid == TRUE ) {
					$this->passwordMD5 = md5( $_SESSION['ias_customer']->password );
				}
			}
			if( is_null( $this->user_agent ) ) {
				$this->user_agent = $_SESSION['HTTP_USER_AGENT'];
			}
			if( is_null( $this->accept_language ) ) {
				$this->accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
			}
			if( is_null( $this->txnID ) ) {
				if( isset( $_SESSION['ias_customer'] ) && isset( $_SESSION['ias_customer']->valid ) && $_SESSION['ias_customer']->valid == TRUE ) {
					$this->txnID = $_SESSION['ias_customer']->id . '_' . $_SESSION['ias_customer']->lastTimeActiveNoFormat . '_' . $_SESSION['ias_customer']->campaignId;
				}
			}
			if( is_null( $this->order_currency ) ) {
				if( isset( $_SESSION['ias_customer'] ) && isset( $_SESSION['ias_customer']->valid ) && $_SESSION['ias_customer']->valid == TRUE ) {
					$this->order_currency = $_SESSION['ias_customer']->currency;
				}
			}
		}

		function run_query() {
			if( is_null( $this->bin ) ) {
				return FALSE;
			}
			$query = array();
			foreach ($this as $key => $value) {
				$query[$key] = $value;
			}
			$body = new Artax\FormBody;
			foreach ($query as $key => $value) {
				if( !is_null( $value ) ) {
					try {
						$body->addField($key,$value);
					}
					catch (exception $e) {
						report_ias_bug( 'Minfraud Error on ' . get_bloginfo('wpurl') , $e->get_error_message() );
	    				return FALSE;
					}
				}
			}
			//https://minfraud.maxmind.com/app/ccv2r
			$client = new Artax\Client;
			$request = (new Artax\Request)->setUri('https://minfraud.maxmind.com/app/ccv2r')->setMethod('POST')->setBody($body);
			try {
    			$response = $client->request($request);
    		}
    		catch (Artax\ClientException $e) {
    			report_ias_bug( 'Minfraud Error on ' . get_bloginfo('wpurl') , $e->get_error_message() );
    			return FALSE;
    		}
    		$return =  $response->getBody();
    		$results = explode(';',$return);
			$returnInfo = array();
			foreach ($results as $string) {
				list($key,$value) = explode('=',$string);
				$returnInfo[$key] = utf8_encode($value);
			}
			if( strlen($returnInfo['err']) > 0 ) {
				$bug_body = '';
				$bug_body .= 'MinFraud API returned the following error:' . "\r\n";
				$bug_body .= '<pre>' . "\r\n";
				$bug_body .= print_r( $returnInfo['err'] , true ) . "\r\n";
				$bug_body .= '</pre>' . "\r\n";
				$bug_body .= 'Query Run:' . "\r\n";
				$bug_body .= '<pre>' . "\r\n";
				$bug_body .= print_r( $this , true ) . "\r\n";
				$bug_body .= '</pre>' . "\r\n";

				report_ias_bug( 'Minfraud Error on ' . get_bloginfo('wpurl') , $bug_body );
			}
			return $returnInfo;
		}

		public static function bin_check( $bin , $amount ) {
			if(strlen($bin) != 6) {
				if(strlen($bin) >= 6) {
					$bin = substr($bin,0,6);
				} else {
					return false;
				}
			}
			$class = __CLASS__;
			$obj = new $class( array( 'bin' => $bin , 'amount' => $amount ) );
			return $obj->run_query();
		}
	} // end ias_cc_fraud_api class
?>