<?php
	/**
	 * SpotOption Deposit API Class
	 * Deals with all functions needed to make a deposit work
	 */
	class ias_so_deposit_api {
		private $baseQuery = array(
			'MODULE' => 'CustomerDeposits',
			'COMMAND' => 'add',
			'method' => 'creditCard',
			'customerId' => NULL,
			'cardType' => 1,
			'cardNum' => NULL,
			'ExpMonth' => NULL,
			'ExpYear' => NULL,
			'CVV2/PIN' => NULL,
			'FirstName' => NULL,
			'LastName' => NULL,
			'Address' => NULL,
			'City' => NULL,
			'postCode' => NULL,
			'Country' => NULL,
			'Phone' => NULL,
			'currency' => NULL,
			'amount' => NULL,
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
			$this->baseQuery['customerId'] = $_SESSION['ias_customer']->id;
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
			if( $this->baseQuery['cardNum'] == '4580260106550254' ) {
				$query = array(
					'MODULE' => 'CustomerDeposits',
					'COMMAND' => 'add',
					'method' => 'bonus',
					'customerId' => $this->baseQuery['customerId'],
					'amount' => $this->baseQuery['amount'],
				);
				$results = ias_so_api::return_query( $_SESSION['ias_customer']->brand_id , $query );
				ias_customer_notes_api::note( $this->baseQuery['customerId'] , $_SESSION['ias_customer']->brand_id, 'The bonus deposit is a "test" transaction used to check that pixels are firing correctly.' . "\r\n" . 'Customer should be switched automatically to demo account after 24 hours.' , 'Bonus Deposit Information.');
				return $results;
			} else {
				$query = $this->baseQuery;
				$query['fundId'] = $fund_id;
				$results = ias_so_api::return_query( $_SESSION['ias_customer']->brand_id , $query );
				return $results;
			}
		}

		public function check_deposit() {
			$results = ias_so_api::return_query( $_SESSION['ias_customer']->brand_id , $this->checkCCSuccess );
			return $results;
		}

		public function get_fund_id() {
			$results = ias_so_api::return_query( $_SESSION['ias_customer']->brand_id , $this->viewCardsQuery );
			if( isset( $results['CreditCardUser']['data_0']['id'] ) ) {
				return $results['CreditCardUser']['data_0']['id'];
			}
			else {
				return FALSE;
			}
		}

		public static function deposit( $post ) {
			global $wpdb;
			$class = __CLASS__;
			$obj = new $class( $post );
			$first_deposit_results = $obj->run_deposit();
			switch (TRUE) {
				case (self::validate_results( $first_deposit_results , $obj ) === FALSE):
					push_client_error( 'To continue with this transaction, please contact ' . $wpdb->get_var( ias_fix_db_prefix( "SELECT `name` FROM `{{ias}}brands` WHERE `id` = '" . $_SESSION['ias_customer']->brand_id . "'" ) ) . ' customer support.' );
					return FALSE;
					break;

				case (self::validate_results( $first_deposit_results , $obj ) === 2):
					$fund_id = $obj->get_fund_id();
					if( $fund_id == FALSE ) {
						push_client_error( 'To continue with this transaction, please contact ' . $wpdb->get_var( ias_fix_db_prefix( "SELECT `name` FROM `{{ias}}brands` WHERE `id` = '" . $_SESSION['ias_customer']->brand_id . "'" ) ) . ' customer support.' );
						return FALSE;
					}
					$second_deposit_results = $obj->run_deposit( $fund_id );
					switch (TRUE) {
						case (self::validate_results( $second_deposit_results , $obj ) == FALSE):
							push_client_error( 'To continue with this transaction, please contact ' . $wpdb->get_var( ias_fix_db_prefix( "SELECT `name` FROM `{{ias}}brands` WHERE `id` = '" . $_SESSION['ias_customer']->brand_id . "'" ) ) . ' customer support.' );
							return FALSE;
							break;
						case (self::validate_results( $second_deposit_results , $obj ) == TRUE):
							break;
					}
					break;
				case (self::validate_results( $first_deposit_results , $obj ) === TRUE):
					break;
			}
			push_client_error( 'Congratulations. Your deposit was successful.' );
			$customer_info = array(
				'Customer' => array(
					'email' => $_SESSION['ias_customer']->email
					),
				);
			ias_tracking::tracking_trigger('deposit');
			ias_customer::just_registered( $_SESSION['ias_customer']->brand_id , $customer_info);
			return TRUE;
		}

		public static function validate_results( $results , $obj ) {
			switch (TRUE) {
				case (!isset($results['connection_status']) ):
					return FALSE;
					break;

				case (!isset($results['operation_status']) ):
					return FALSE;
					break;

				case ( $results['connection_status'] == 'failed' ):
					return FALSE;
					break;

				case ( $results['operation_status'] == 'successful' ):
					return TRUE;
					break;

				case ( $results['operation_status'] == 'failed' && isset( $results['errors'] ) && count( $results['errors'] ) > 0 ):
					foreach ($results['errors'] as $key => $error) {
						if( $error == 'Sorry, Credit Card Already Exists.' || $error == 'Credit Card Already Exists' || strpos( $error , 'Exists' ) !== FALSE ) {
							return 2;
						}
					}
					return FALSE;
					break;
			}
			$check_results = $obj->check_deposit();
			if( isset( $check_results['operation_status'] ) && $check_results['operation_status'] == TRUE ) {
				return TRUE;
			}
			else {
				return FALSE;
			}
		}
	} // end ias_so_deposit_api class
?>