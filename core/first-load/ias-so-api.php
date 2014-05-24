<?php
/**
 * Class for interacting with SpotOption API
 * Uses @Artax Library instead of wp_remote_request library due to not wanting to have to deal with cURL at any point in the transaction
 */
class ias_so_api {
	private $api_url = NULL;
	private $api_user = NULL;
	private $api_pass = NULL;
	private $return_raw = NULL;
	private $last_query = NULL;
	public $result = NULL;

	function __construct( $brand ) {
		global $wpdb;
		$brand = $wpdb->get_row( ias_fix_db_prefix( "SELECT * FROM `{{ias}}brands` WHERE `id` = '" . $brand . "'" ), ARRAY_A);
		if( isset($brand['isBDB'] ) && $brand['isBDB'] == 1 ) {
			$this->get_credentials_from_license_server( $brand['licenseKey'] );
		}
		else if ( isset($brand['isBDB'] ) && $brand['isBDB'] == 0 ) {
			$this->api_url = $brand['apiURL'];
			$this->api_user = $brand['apiUser'];
			$this->api_pass = $brand['apiPass'];
		}
	}

	private function get_credentials_from_license_server( $key ) {
		$uri = 'http://licensing.streaming-signals.com/?key=' . $key;
		$client = new Artax\Client;
		$request = (new Artax\Request)->setUri($uri)->setMethod('GET');
		try {
    		$response = $client->request($request);
    		$results =  $response->getBody();
    	}
    	catch (Artax\ClientException $e) {
    		$results = NULL;
    	}
    	if( !is_null( $results ) ) {
			$json = base64_decode( $results );
			try {
				$creds_array = json_decode( $json , true );
			}
			catch (exception $e) {
				return FALSE;
			}
    	} else {
    		return FALSE;
    	}
    	$this->api_url = $creds_array['api_url'];
    	$this->api_user = $creds_array['api_user'];
    	$this->api_pass = $creds_array['api_pass'];
	}

	private function xml2array ( $xmlObject, $out = array () ) {
	    foreach ( (array) $xmlObject as $index => $node )
	        $out[$index] = ( is_object ( $node ) ) ? $this->xml2array ( $node ) : $node;
	    return $out;
	}

	public function run_query( $query = NULL ) {
		if(!is_array( $query ) && !is_null( $query ) ) {
			return FALSE;
		}
		if( is_null($query) && is_null($this->last_query) ) {
			return FALSE;
		}
		else if ( is_null($query) ) {
			$query = $this->last_query;
		}
		$submission = array();
		$submission['api_username'] = $this->api_user;
		$submission['api_password'] = $this->api_pass;
		foreach ($query as $key => $value) {
			$submission[$key] = $value;
		}
		$body = new Artax\FormBody;
		foreach ($submission as $key => $value) {
			$body->addField($key,$value);
		}
		$client = new Artax\Client;
		$request = (new Artax\Request)->setUri($this->api_url)->setMethod('POST')->setBody($body);
		try {
    		$response = $client->request($request);
    		$results =  $response->getBody();
    		$this->lastResultsRaw = $results;
    	} catch (Artax\ClientException $e) {
    		$this->lastResultsRaw = "<?xml version=\"1.0\"?><connection_status>failed</connection_status><operation_status>failed</operation_status>";
    	}

    	try {
    		$xml = new SimpleXMLElement( $this->lastResultsRaw  );
    	}
    	catch( Exception $e ) {
    		$rm_error = 'Spot API has returned results which could not be understood by the standard XML parser.' . "\r\n";
    		$rm_error .= 'Response from SpotAPI' . "\r\n";
    		$rm_error .= '<pre>' . "\r\n";
    		$rm_error .= print_r( $this->lastResultsRaw , TRUE ) . "\r\n";
    		$rm_error .= '</pre>' . "\r\n";
    		$rm_error .= 'The following exception was triggered: ' . "\r\n";
    		$rm_error .= '<pre>' . "\r\n";
    		$rm_error .= $e . "\r\n";
    		$rm_error .= '</pre>' . "\r\n";
    		report_ias_bug( 'API Return Error on site ' . get_bloginfo('wpurl') , $rm_error );
    		$this->lastResultsRaw = "<?xml version=\"1.0\"?><connection_status>failed</connection_status><operation_status>failed</operation_status>";
    		return FALSE;
    	}
    	$processed = $this->xml2array($xml);
    	$this->last_query = $query;
    	$this->result = $processed;
	}

	public static function return_query( $brand , $query ) {
		$class = __CLASS__;
		$obj = new $class( $brand );
		$obj->run_query( $query );
		return $obj->result;
	}
}
?>