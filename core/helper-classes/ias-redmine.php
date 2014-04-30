<?php
	/**
	 * Class for interacting with the rm.14all.me Redmine API
	 * Uses Artax
	 */
	
	class ias_redmine {
		private $api_key = '59930c6460e8e71ef58b4cc95d852153bf21b510';
		private $basic_user = 'ias';
		private $basic_pass = '1q2w3e$r';
		private $url = 'https://rm.14all.me/issues.json';
		private $mime = 'application/json';
		private $method = 'POST';
		public $return = NULL;
		public $response = NULL;
		
		function __construct( $info ) {
			$body = json_encode( $info );
			$client = new Artax\Client;
			$request = (new Artax\Request)->setUri( $this->url );
			$request->setProtocol('1.0');
			$request->setMethod( $this->method );
			$request->setAllHeaders([
				'Content-Type' => $this->mime,
				'x-api-key' => '59930c6460e8e71ef58b4cc95d852153bf21b510',
				'X-Redmine-API-Key' => '59930c6460e8e71ef58b4cc95d852153bf21b510',
			]);
			$request->setBody( $body );
			try {
				$this->return = $client->request($request);
				$this->response = $this->return->getStatus();
			}
			catch (Artax\ClientException $e) {
			    $this->return = $e;
			}
		}
	} // end of ias_redmine class
?>