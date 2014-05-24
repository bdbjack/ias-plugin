<?php
/**
 * IAS Customer Class
 * This Object is used to get the information about the user from all relevant sources including:
 * - SpotAPI
 * - Local DB (if available)
 */
 class ias_customer {
	
	function __construct( $brand , $input , $justRegistered = FALSE) {
		switch (TRUE) {
			case ($justRegistered == TRUE):
				break;

			case (is_array($input) && isset($input['email']) && isset($input['password'])):
				$query = array(
						'MODULE' => 'Customer',
						'COMMAND' => 'view',
						'FILTER[email]' => $input['email'],
						'FILTER[password]' => $input['password'],
					);
				$noResultsError = 'Your login credentials could not be validated. Please check your credentials and try again.';
				break;

			case (is_array($input) && isset($input['email']) && !isset($input['password'])):
				$query = array(
						'MODULE' => 'Customer',
						'COMMAND' => 'view',
						'FILTER[email]' => $input['email'],
					);
				$noResultsError = 'Your user information could not be retrieved from your broker at this time. Please try again later.';
				break;

			case (is_array($input) && isset($input['id'])):
				$query = array(
						'MODULE' => 'Customer',
						'COMMAND' => 'view',
						'FILTER[id]' => $input['id'],
					);
				$noResultsError = 'Your user information could not be retrieved from your broker at this time. Please try again later.';
				break;

			case (!is_array($input) && is_numeric($input)):
				$query = array(
						'MODULE' => 'Customer',
						'COMMAND' => 'view',
						'FILTER[id]' => $input,
					);
				$noResultsError = 'Your user information could not be retrieved from your broker at this time. Please try again later.';
				break;

			case (!is_array($input) && !is_numeric($input)):
				$query = array(
						'MODULE' => 'Customer',
						'COMMAND' => 'view',
						'FILTER[email]' => $input,
					);
				$noResultsError = 'Your user information could not be retrieved from your broker at this time. Please try again later.';
				break;
		}
		if( $justRegistered == FALSE ) {
			$result = ias_so_api::return_query( $brand , $query );
		}
		else {
			$result['Customer']['data_0'] = $input;
			$result['connection_status'] = 'successful';
			$result['operation_status'] = 'successful';
			$noResultsError = '';
			$noResultsError = '';
		}
		$errors = array(
			'noResults' => $noResultsError,
			'invalidCustomerId' => $noResultsError,
			'addFailed' => 'We were not able to register your information with the broker you selected at this time.',
			'requiredFieldsMissing' => 'You are missing some required information.',
			'invalidFirstName' => 'Please check that you have entered your first name correctly.',
			'invalidLastName' => 'Please check that you have entered your last name correctly.',
			'invalidGender' => 'Please check that you have chosen a gender.',
			'invalidEmail' => 'Please submit a valid email address.',
			'emailAlreadyExists' => 'The email you are attempting to register with already exists in our system. Please try to log in or speak with your broker to reset your password.',
			'passwordTooshort' => 'The password that you have chosen is too short. Please try a longer password.',
			'invalidAuthKey' => 'There was a general issue with your registration. Please try again in a few moments.',
			'invalidCellPhoneNo' => 'The cellphone number that you provided is invalid. Please check the number and try again.',
			'invalidPhoneNo' => 'The phone number you have provided is invalid. Please check the number and try again.',
			'invalidPersonalId' => 'There was a general issue with your registration. Please try again in a few moments.',
			'invalidFaxNo' => 'The fax number you have provided is invalid. Please check the number and try again.',
			'invalidCountry' => 'The country you are trying to register from is not allowed to register with this broker.',
			'invalidRegistrationCountry' => 'The country you are trying to register from is not allowed to register with this broker.',
			'invalidState' => 'Please enter only 2 digits for a state code',
			'invalidCity' => 'The city name that you have provided is too short.',
			'invalidStreetAddress' => 'The street address you have provided is not valid. Please check the street address and try again.',
			'invalidCurrency' => 'This broker does not allow users to register with this currency. Please choose a different currency and try again.',
			'invalidHouseNo' => 'Please provide a valid house number.',
			'invalidApartmentNo' => 'Please provide a valid apartment identification.',
			'invalidApprovesEmailAdsStatus' => 'There was a general issue with your registration. Please try again in a few moments.',
			'invalidPostalCode' => 'The postal code you have provided is not valid. Please check your postal code and try again.',
			'invalidCampaignId' => 'There was a general issue with your registration. Please try again in a few moments.',
			'invalidSubCampaign' => 'There was a general issue with your registration. Please try again in a few moments.',
			'invalidAffiliateId' => 'There was a general issue with your registration. Please try again in a few moments.',
			'invalidBirthday' => 'There was a general issue with your registration. Please try again in a few moments.',
			'customerTooYoung' => 'You are too young to register an account with this broker. Please try a different broker.',
			'invalidEmployee' => 'There was a general issue with your registration. Please try again in a few moments.',
			'invalidDate' => 'There was a general issue with your registration. Please try again in a few moments.',
			'invalidPotential' => 'There was a general issue with your registration. Please try again in a few moments.',
			'invalidRisk' => 'There was a general issue with your registration. Please try again in a few moments.',
			'invaludRegStatus' => 'There was a general issue with your registration. Please try again in a few moments.',
			'invalidSaleStatus' => 'There was a general issue with your registration. Please try again in a few moments.',
			'invalidCustomerGroup' => 'There was a general issue with your registration. Please try again in a few moments.',
			'invalidReferLink' => 'There was a general issue with your registration. Please try again in a few moments.',
			'invalidIsDemo' => 'There was a general issue with your registration. Please try again in a few moments.',
			'invalidSpecialAccountNumber' => 'There was a general issue with your registration. Please try again in a few moments.',
			'specialAccountNumberExists' => 'There was a general issue with your registration. Please try again in a few moments.',
		);
		if( $result['connection_status'] != 'successful' || $result['operation_status'] != 'successful' ) {
			switch (TRUE) {
				case ($result['connection_status'] != 'successful'):
					push_client_error( 'Your broker is currently not available for login. Please try again in a few minutes' );
					break;
				
				default:
					foreach ($result['errors'] as $key => $error) {
						if(isset($errors[$error])) {
							push_client_error( $errors[$error] );
						} else {
							push_client_error( 'There was a general issue performing this action. Please try again later' );
						}
					}
					break;
			}
			$this->valid = FALSE;
		} else {
			foreach ($result['Customer']['data_0'] as $key => $value) {
				$this->$key = (!is_array($value)) ? $value : NULL;
			}
			if(isset($result['Customer']['data_0']['Country'])) {
				$this->country_id = $this->get_country_id( $result['Customer']['data_0']['Country'] );
			}
			if(isset($result['Customer']['data_0']['registrationCountry'])) {
				$this->reg_country_id = $this->get_country_id( $result['Customer']['data_0']['registrationCountry'] );
			}
			$this->brand_id = $brand;
			$this->valid = TRUE;
		}
	}

	public static function login_validate( $brand, $email , $password ) {
		$info = array(
			'email' => $email,
			'password' => $password,
		);
		$class = __CLASS__;
		$cust = new $class( $brand, $info );
		return $cust;
	}

	public static function just_registered( $brand , $spot_return ) {
		global $ias_session;
		if( !isset($spot_return['Customer']) ) {
			return FALSE;
		}
		$info = $spot_return['Customer'];
		$class = __CLASS__;
		$cust = new $class( $brand , $info['email'] );
		$_SESSION['ias_customer'] = $cust;
		$ias_session['ias_customer'] = $cust;
	}

	public static function reload_customer_information() {
		if( !isset($_SESSION['ias_customer']) ) {
			return FALSE;
		}
		$class = __CLASS__;
		$cust = new $class( $_SESSION['ias_customer']->email , $_SESSION['ias_customer']->brand_id );
		$ias_session['ias_customer'] = $cust;
	}

	private function get_country_id( $country_name ) {
		global $wpdb;
		return $wpdb->get_var( ias_fix_db_prefix("SELECT `id` FROM `{{ias}}countries` WHERE `name` LIKE '" . $country_name . "'") );
	}

 } // end of ias_customer class
?>