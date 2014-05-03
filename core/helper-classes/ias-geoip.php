<?php
	/**
	 * IAS GeoIP Detection Class
	 * This class should be loaded ONCE into the session once the session is initiated, and then the object should be copied to a global variable
	 */
	
	class ias_geoip {
		public $ip = NULL;
		public $iso = NULL;
		public $spotid = NULL;
		public $region = NULL;
		public $prefix = NULL;
		public $countryName = NULL;
		
		function __construct( $iso = NULL , $spotid = NULL , $getomni = TRUE , $ip = NULL ) {
			if(is_null($ip)) {
				$ip = ip();
			}
			$this->ip = $ip;
			if( !is_null($iso) || !is_null($spotid) ) {
				$getomni = FALSE;
				$this->iso = $iso;
				$this->spotid = $spotid;
			}
			if( $getomni == TRUE ) {
				$this->get_omni_info();
			}
			$this->get_country_info();
		}

		private function get_omni_info() {
			$APIURL = "https://geoip.maxmind.com/e?i=" . $this->ip . "&l=XTz2Lfu6u3M1";
			$server_reply = wp_remote_get($APIURL);
			if(  is_wp_error($server_reply) ) {
				return FALSE;
			}
			$omniInfo = array();
			$omni_keys = array(
			    'country_code',
			    'country_name',
			    'region_code',
			    'region_name',
			    'city_name',
			    'latitude',
			    'longitude',
			    'metro_code',
			    'area_code',
			    'time_zone',
			    'continent_code',
			    'postal_code',
			    'isp_name',
			    'organization_name',
			    'domain',
			    'as_number',
			    'netspeed',
			    'user_type',
			    'accuracy_radius',
			    'country_confidence',
			    'city_confidence',
			    'region_confidence',
			    'postal_confidence',
			    'error',
			);
	    	$omni_values = str_getcsv($server_reply['body']);
			$omni_values = array_pad($omni_values, sizeof($omni_keys), '');
			$omniInfo = array_combine($omni_keys, $omni_values);
			$this->omni = $omniInfo;
			$this->iso = $omniInfo['country_code'];
		}

		private function get_country_info() {
			global $wpdb;
			if( !is_null( $this->spotid ) ) {
				$country = $wpdb->get_row( ias_fix_db_prefix("SELECT * FROM `{{ias}}countries` WHERE `id` LIKE '" . $this->spotid . "'") , ARRAY_A );
				$this->spotid = $country['id'];
				$this->region = $country['region'];
				$this->prefix = $country['prefix'];
				$this->countryName = $country['name'];
				$this->iso = $country['ISO'];
			} else {
				$country = $wpdb->get_row( ias_fix_db_prefix("SELECT * FROM `{{ias}}countries` WHERE `ISO` LIKE '" . $this->iso . "'") , ARRAY_A );
				$this->spotid = $country['id'];
				$this->region = $country['region'];
				$this->prefix = $country['prefix'];
				$this->countryName = $country['name'];
			}
		}

		public static function country_info( $country ) {
			$type = NULL;
			if( is_numeric( $country ) ) {
				$type = 'spotid';
			}
			else {
				$type = 'iso';
			}
			$class = get_class();
			switch ($type) {
				case 'spotid':
					$info = new $class( $iso = NULL , $spotid = $country );
					break;
				
				default:
					$info = new $class( $iso = $country );
					break;
			}
			return $info;
		}
	}
?>