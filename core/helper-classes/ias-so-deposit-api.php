<?php
	/**
	 * SpotOption Deposit API Class
	 * Deals with all functions needed to make a deposit work
	 */
	class ias_so_deposit_api {
		private $baseQuery = array(
			'MODULE' => 'CustomerDeposits',
			'COMMAND' => 'add',
			'method' => 'creditCard';
			'customer' => NULL;
			'cardType' => 1;
			'cardNum' => NULL;
			'ExpMonth' => NULL;
			'ExpYear' => NULL;
			'CVV2/PIN' => NULL;
			'FirstName' => NULL;
			'LastName' => NULL;
			'Address' => NULL;
			'City' => NULL;
			'postCode' => NULL;
			'Country' => NULL;
			'Phone' => NULL;
			'currency' => NULL;
			'amount' => NULL;
		);

		private $viewCardsQuery = array(
			'MODULE' => 'CreditCardUser',
			'COMMAND' => 'view',
			'FILTER[cardNum]' => NULL,
			'FILTER[customerId]' => NULL,
		);

		private $checkCCSuccess = array(
			'MODULE' => 'CustomerDeposits',
	    	'COMMAND' => 'view',
	    	'FILTER[customerId]' => NULL,
	    	'FILTER[amount]' => NULL,
	    	'FILTER[requestTime][min]' => NULL,
	    	'FILTER[status]' => 'approved',
		);

		function __construct( $post ) {
			$this->baseQuery['customer'] = $_SESSION['ias_customer']->id;
			$this->viewCardsQuery['FILTER[customerId]'] = $_SESSION['ias_customer']->id;
			$this->checkCCSuccess['FILTER[customerId]'] = $_SESSION['ias_customer']->id;
			$this->baseQuery['FirstName'] = $_SESSION['ias_customer']->FirstName;
			$this->baseQuery['LastName'] = $_SESSION['ias_customer']->LastName;
			$this->baseQuery['Phone'] = $_SESSION['ias_customer']->phone;
			$this->baseQuery['currency'] = $_SESSION['ias_customer']->currency;
			$this->baseQuery['cardNum'] = $post['ccNumber'];
			$this->viewCardsQuery['FILTER[cardNum]'] = substr($post['ccNumber'],-4);
			$this->baseQuery['ExpMonth'] = $post['expMonth'];
			$this->baseQuery['ExpYear'] = $post['expYear'];
			$this->baseQuery['CVV2/PIN'] = $post['cvv'];
			$this->baseQuery['Address'] = $post['address'];
			$this->baseQuery['City'] = $post['city'];
			$this->baseQuery['postCode'] = $post['postal'];
			$this->baseQuery['Country'] = $post['country'];
			$this->baseQuery['amount'] = $post['amount'];
			$this->checkCCSuccess['FILTER[amount]'] = $post['amount'];
			$this->checkCCSuccess['FILTER[requestTime][min]'] = date('Y-m-d H:i:s',time() - 120);
		}

		public function run_deposit( $fund_id = -1 ) {
			$query = $this->baseQuery;
			$query['fundId'] = $fund_id;
			$results = ias_so_api::return_query( $_SESSION['ias_customer']->brand_id , $query );
			return $results;
		}

		public function check_deposit() {
			$results = ias_so_api::return_query( $_SESSION['ias_customer']->brand_id , $this->checkCCSuccess );
			return $results;
		}

		public static function deposit( $post ) {
			
		}
	} // end ias_so_deposit_api class
?>