<?php
/**
 * Deposit Form Class for IAS
 */
 class ias_deposit_form extends ias_forms {
		private $defaultLayout = array(
			array('action'),
			array('amount'),
			array('ccNumber'),
			array('cvv','expMonth','expYear'),
			array('fName','lName'),
			array('email','phone'),
			array('address'),
			array('city'),
			array('state','postal'),
			array('country'),
			array('submit'),
		);

		function __construct( $layout = NULL , $override = array() ) {
			global $ias_session, $wpdb;
			if( isset($_SESSION['ias_customer']->Country) && is_numeric($_SESSION['ias_customer']->Country) ) {
				$country = $_SESSION['ias_customer']->Country;
			} else {
				$country = $_SESSION['ias_customer']->country_id;
			}
			$years_array = array();
			$current_year = date('Y');
			$max_year = $current_year + 20;
			while( $current_year <= $max_year ) {
				$option = array( 
					'value' => $current_year,
					'name' => $current_year,
				);
				array_push($years_array, $option);
				$current_year ++;
			}
			foreach ($_SESSION['perma_get'] as $key => $value) {
				$$key = $value;
			}
			foreach ($_POST as $key => $value) {
				$$key = $value;
			}
			$fields = array(
				'action' => array(
					'type' => 'hidden',
					'name' => 'action',
					'label' => NULL,
					'placeholder' => NULL,
					'attributes' => array(),
					'value' => 'makeDeposit',
					'validate' => FALSE,
					),
				'amount' => array(
					'type' => 'currency',
					'name' => 'amount',
					'label' => 'Deposit Amount',
					'placeholder' => 'Deposit Amount',
					'attributes' => array(
							'required' => 'required',
						),
					'value' => ( isset($amount) ) ? $amount : 500,
					'validate' => array(
							'rules' => array(
									'digits' => TRUE,
									'min' => 100,
								),
							'messages' => array(
									'digits' => 'Please enter only numbers.',
									'min' => 'The minimum deposit is ' . $_SESSION['ias_customer']->currency . ' {0}.',
								),
						),
					),
				'fName' => array(
					'type' => 'text',
					'name' => 'fName',
					'label' => 'First Name',
					'placeholder' => 'First Name',
					'attributes' => array(
							'required' => 'required',
						),
					'value' => ( isset($fName) ) ? $fName : $_SESSION['ias_customer']->FirstName,
					'validate' => FALSE,
					),
				'lName' => array(
					'type' => 'text',
					'name' => 'lName',
					'label' => 'Last Name',
					'placeholder' => 'Last Name',
					'attributes' => array(
							'required' => 'required',
						),
					'value' => ( isset($lName) ) ? $lName : $_SESSION['ias_customer']->LastName,
					'validate' => FALSE,
					),
				'email' => array(
					'type' => 'email',
					'name' => 'email',
					'label' => 'Email Address',
					'placeholder' => 'Email Address',
					'attributes' => array(
							'required' => 'required',
						),
					'value' => ( isset($email) ) ? $email : $_SESSION['ias_customer']->email,
					'validate' => array(
							'rules' => array(
									'email' => TRUE,
								),
							'messages' => array(
									'email' => 'Please enter your account email to continue',
								),
						),
					),
				'phone' => array(
					'type' => 'tel',
					'name' => 'phone',
					'label' => 'Main Phone',
					'placeholder' => 'Main Phone',
					'attributes' => array(
							'required' => 'required',
						),
					'value' => ( isset($phone) ) ? $phone : $_SESSION['ias_customer']->phone,
					'validate' => array(
							'rules' => array(
									'digits' => TRUE,
									'phoneVal' => TRUE,
								),
							'messages' => array(
									'digits' => 'Please enter only digits ( 0 - 9 ) with no + or -',
									'phoneVal' => 'Please enter a valid phone number',
								),
						),
					),
				'country' => array(
					'type' => 'select',
					'name' => 'country',
					'label' => 'Country',
					'placeholder' => 'Country',
					'attributes' => array(
							'required' => 'required',
						),
					'value' => $wpdb->get_results( ias_fix_db_prefix( "SELECT  `{{ias}}countries`.`id` as `value`,  `{{ias}}countries`.`name`,  `{{ias}}countries`.`prefix`, `{{ias}}countries`.`ISO` as `iso`,  `{{ias}}countries`.`region`  FROM `{{ias}}countries` LEFT JOIN `{{ias}}regions` ON `{{ias}}countries`.`region` = `{{ias}}regions`.`id` WHERE  `{{ias}}countries`.`id` NOT LIKE '0' AND `{{ias}}regions`.`brands` NOT LIKE '[]'" ), ARRAY_A),
					'default' => $country,
					'validate' => FALSE,
					),
				'address' => array(
					'type' => 'text',
					'name' => 'address',
					'label' => 'Street Address',
					'placeholder' => 'Street Address',
					'attributes' => array(
							'required' => 'required',
						),
					'value' => ( isset($address) ) ? $address : NULL,
					'validate' => FALSE,
					),
				'city' => array(
					'type' => 'text',
					'name' => 'city',
					'label' => 'City',
					'placeholder' => 'City',
					'attributes' => array(
							'required' => 'required',
						),
					'value' => ( isset($city) ) ? $city : $_SESSION['ias_geoip']->omni['city_name'],
					'validate' => FALSE,
					),
				'state' => array(
					'type' => 'text',
					'name' => 'state',
					'label' => 'State',
					'placeholder' => 'State',
					'attributes' => array(
							'required' => 'required',
						),
					'value' => ( isset($state) ) ? $state : $_SESSION['ias_geoip']->omni['region_code'],
					'validate' => array(
							'rules' => array(
								'required' => TRUE,
								'minlength' => 2,
								'maxlength' => 2,
							),
							'messages' => array(
								'required' => 'Please enter the 2 digit abbreviation for your state of residence.',
								'minlength' => 'Please enter at least {0} characters',
								'maxlength' => 'Please enter no more than {0} characters',
							),
						),
					),
				'postal' => array(
					'type' => 'text',
					'name' => 'postal',
					'label' => 'Postal Code (Zip)',
					'placeholder' => 'Postal Code (Zip)',
					'attributes' => array(
							'required' => 'required',
						),
					'value' => ( isset($postal) ) ? $postal : $_SESSION['ias_geoip']->omni['postal_code'],
					'validate' => FALSE,
					),
				'ccNumber' => array(
						'type' => 'tel',
						'name' => 'ccNumber',
						'label' => 'Credit Card Number',
						'placeholder' => 'Credit Card Number',
						'attributes' => array(
								'required' => 'required',
							),
						'value' => ( isset($ccNumber) ) ? $ccNumber : NULL,
						'validate' => array(
								'rules' => array(
									'required' => TRUE,
									'digits' => TRUE,
									'minlength' => 16,
									'creditcard' => TRUE,
									),
								'messages' => array(
									'required' => 'You must enter a credit card number to deposit.',
									'digits' => 'Please enter only digits ( 0 - 9 ) with no spaces or dashes.',
									'minlength' => 'Please enter a full credit card number.',
									'creditcard' => 'Please enter a valid credit card number.',
									),
							),
					),
				'cvv' => array(
						'type' => 'tel',
						'name' => 'cvv',
						'label' => 'CVV',
						'placeholder' => 'CVV',
						'attributes' => array(
								'required' => 'required',
							),
						'value' => ( isset($cvv) ) ? $cvv : NULL,
						'validate' => array(
								'rules' => array(
									'required' => TRUE,
									'minlength' => 3,
									'maxlength' => 4,
									),
								'messages' => array(
									'required' => 'Please enter your CVV.',
									'minlength' => 'Please enter at least {0} numbers.',
									'maxlength' => 'Please enter no more than {0} numbers.',
									),
							),
					),
				'expMonth' => array(
						'type' => 'select',
						'name' => 'expMonth',
						'label' => 'Expiration Month',
						'placeholder' => 'Expiration Month',
						'attributes' => array(
								'required' => 'required',
							),
						'value' => array(
								array( 'value' => '01' , 'name' => 'January' ),
								array( 'value' => '02' , 'name' => 'February' ),
								array( 'value' => '03' , 'name' => 'March' ),
								array( 'value' => '04' , 'name' => 'April' ),
								array( 'value' => '05' , 'name' => 'May' ),
								array( 'value' => '06' , 'name' => 'June' ),
								array( 'value' => '07' , 'name' => 'July' ),
								array( 'value' => '08' , 'name' => 'August' ),
								array( 'value' => '09' , 'name' => 'September' ),
								array( 'value' => '10' , 'name' => 'October' ),
								array( 'value' => '11' , 'name' => 'November' ),
								array( 'value' => '12' , 'name' => 'December' ),
							),
						'default' => ( isset($expMonth) ) ? $expMonth : date('m') + 1,
						'validate' => FALSE,
					),
				'expYear' => array(
						'type' => 'select',
						'name' => 'expYear',
						'label' => 'Expiration Year',
						'placeholder' => 'Expiration Year',
						'attributes' => array(
								'required' => 'required',
							),
						'value' => $years_array,
						'default' => ( isset($expYear) ) ? $expYear : date('Y'),
						'validate' => FALSE,
					),
				'submit' => array(
					'type' => 'submit',
					'name' => NULL,
					'label' => NULL,
					'placeholder' => NULL,
					'attributes' => array(
							'required' => 'required',
						),
					'value' => 'Register',
					'validate' => FALSE,
					),
				);
			$this->fields = $fields;
			$header_js = '';
			$header_js .= 'function checkPhone( phone , elem ) {' . "\r\n";
			$header_js .= '	or_elem = jQuery(elem);' . "\r\n";
			$header_js .= '	form_id = or_elem.attr(\'id\').replace(\'_phone\',\'\');' . "\r\n";
			$header_js .= '	form_id = form_id.replace(\'_cellphone\',\'\');' . "\r\n";
			$header_js .= '	iso = jQuery("#" + form_id +"_country option:selected").attr(\'data-iso\');' . "\r\n";
			$header_js .= '	var validatedResults = isValidNumber( phone , iso );' . "\r\n";
			$header_js .= '	return validatedResults;' . "\r\n";
			$header_js .= '}' . "\r\n";
			$header_js .= 'function numbersonly(e,t,n){var r;var i;if(window.event)r=window.event.keyCode;else if(t)r=t.which;else return true;i=String.fromCharCode(r);if(r==null||r==0||r==8||r==9||r==13||r==27)return true;else if("0123456789".indexOf(i)>-1)return true;else if(n&&i=="."){e.form.elements[n].focus();return false}else return false}' . "\r\n";
			$header_js .= 'jQuery.validator.addMethod("phoneVal", function(value, element, params) {' . "\r\n";
			$header_js .= ' return this.optional(element) || checkPhone( value , element );' . "\r\n";
			$header_js .= '}, jQuery.validator.format("The number you have entered is not a valid phone number."));' . "\r\n";
			$header_js .= 'jQuery.validator.addMethod("cellPhoneVal", function(value, element, params) {' . "\r\n";
			$header_js .= ' return this.optional(element) || checkPhone( value , element );' . "\r\n";
			$header_js .= '}, jQuery.validator.format("The number you have entered is not a valid mobile phone number."));' . "\r\n";
			$this->update_form_head_js( $header_js );
			if(!is_null($layout)) {
				$this->update_form_layout( $layout );
			}
			else {
				$this->update_form_layout( $this->defaultLayout );
			}
			//$this->set_form_action('javascript:false;');
			$this->get_form_html();
		}

		public static function shortcode( $atts, $content = NULL ) {
			if(!isset($_SESSION['ias_customer']) || $_SESSION['ias_customer']->valid == FALSE ) {
				$html = '';
				$html .= '<div style="width: 100%">' . "\r\n";
				$html .= '	<h2>' . __('Please log in to continue:' , IAS_TEXTDOMAIN ) . '</h2>' . "\r\n";
				$html .= '	<div style="width: 350px; margin: 0 auto; margin-top: 20px; margin-bottom: 20px;">' . "\r\n";
				$html .= ias_login_form::shortcode('') . "\r\n";
				$html .= '	</div>' . "\r\n";
				$html .= '</div>' . "\r\n";
				return $html;
			}
			$class = get_class();
			$req_fields = array(
				'action',
				'amount',
				'fName',
				'lName',
				'email',
				'phone',
				'country',
				'address',
				'city',
				'state',
				'postal',
				'ccNumber',
				'cvv',
				'expMonth',
				'expYear',
				'submit',
			);
			$possible_atts = array(
				'use_chosen' => 'use_chosen',
				'use_validate' => 'use_validate',
				'use_captcha' => 'use_captcha',
				'response_size' => 'set_response_size',
				'form_head_css' => 'update_form_head_css',
				'form_head_js' => 'update_form_head_js',
				'form_foot_html' => 'update_form_foot_html',
				'form_foot_css' => 'update_form_foot_css',
				'form_foot_js' => 'update_form_foot_js',
				'form_action' => 'set_form_action',
				'form_method' => 'set_form_method',
				'form_charset' => 'set_form_charset',
				'form_autocomplete' => 'set_form_autocomplete',
				'form_enctype' => 'set_form_enctype',
				'form_target' => 'set_form_target',
				'form_attributes' => 'add_form_attributes',
			);
			if(!is_null($content)) {
				$whole = explode("\r\n", $content);
				$layout = array();
				$existing_fields = array();
				foreach ($whole as $row) {
					if( $row != '' ) {
						$row_array = explode(',', $row);
						$return_row = array();
						foreach ($row_array as $field) {
							if($field != '' && !in_array($field, $existing_fields)) {
								array_push($return_row, $field);
								array_push($existing_fields, $field);
							}
						}
						array_push($layout, $return_row);
					}
				}
				foreach ($req_fields as $field) {
					if( !in_array( $field, $existing_fields ) ) {
						array_push($layout, array($field) );
					}
				}
			} else {
				$layout = NULL;
			}
			$form = new $class( $layout );
			if(is_array($atts)) {
				foreach ($atts as $att => $value) {
					if(isset($possible_atts[$att])) {
						$function = $possible_atts[$att];
						$form->$function( $value );
					}
				}
				$form->regenerate();
			}
			return $form->html;
		}

		public static function action() {
			global $ias_session, $wpdb;
			$errors = array();
			$req_fields = array(
				'amount' => 'You must enter an amount to deposit',
				'fName' => 'You are missing your First Name. Please enter your first name and re-submit.',
				'lName' => 'You are missing your Last Name. Please enter your last name and re-submit.',
				'email' => 'You are missing your email. Please enter your email and re-submit.',
				'phone' => 'You are missing your phone number. Please enter your phone number and re-submit.',
				'country' => 'You are missing your billing country. Please enter your billing country and re-submit.',
				'address' => 'You are missing your billing address. Please enter your billing address and re-submit.',
				'city' => 'You are missing your billing city. Please enter your billing city and re-submit.',
				'state' => 'You are missing your billing state. Please enter your billing state and re-submit.',
				'postal' => 'You are missing your billing postal code. Please enter your billing postal code and re-submit.',
				'ccNumber' => 'You are missing your credit card number. Please enter your credit card number and re-submit.',
				'cvv' => 'You are missing your CVV. Please enter your CVV and re-submit.',
				'expMonth' => 'You are missing your expiration month. Please enter your expiration month and re-submit.',
				'expYear' => 'You are missing your expiration year. Please enter your expiration year and re-submit.',
			);
			foreach ($req_fields as $key => $error) {
				if(!isset($_POST[$key]) || strlen($_POST[$key]) == 0) {
					array_push($errors, $error);
				}
			}
			if( count($errors) !== 0 ) {
				foreach ($errors as $error) {
					push_client_error( $error );
				}
				return FALSE;
			}
			// Perform Server Side validation checks
			if( !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ) {
				push_client_error( 'The email address you have entered is not valid. Please enter a valid email and try again.' );
				return FALSE;
			}
			$atsignpos = strpos( $_POST['email'] , '@' );
			$domain = substr( $_POST['email'], $atsignpos );
			$domain = str_replace('@', '', $domain);
			if( !checkdnsrr( $domain , 'MX' ) ) {
				push_client_error( 'Your email domain does not seem to be valid. Please check the domain of your email (' . $domain .') and try again.' );
				return FALSE;
			}
			$iso = $wpdb->get_var( ias_fix_db_prefix("SELECT `ISO` FROM `{{ias}}countries` WHERE `id` = " . $_POST['country'] . " ") );
			$phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
			try {
				$post_phone_lib = $phoneUtil->parse( $_POST['phone'] , $iso );
				if( $phoneUtil->isPossibleNumber( $post_phone_lib ) == FALSE ) {
					push_client_error( 'The phone number you have entered is invalid. Please check your details and try again.' );
					return FALSE;
				}
			}
			catch (\libphonenumber\NumberParseException $e) {
				push_client_error( 'There was an issue validating your phone number. Please check your details and try again.' );
				return FALSE;
			}
			# CC Number
			if( self::luhn_check( $_POST['ccNumber'] ) == FALSE ) {
				push_client_error( 'The credit card number you have entered is not valid. Please enter a valid credit card number and try again.' );
				return FALSE;
			}
			# CVV
			if( strlen( $_POST['cvv'] ) < 3 || strlen( $_POST['cvv'] ) > 5 ) {
				push_client_error( 'The CVV you have entered is invalid. Please check your CVV and try again.' );
				return FALSE;
			}
			# Expiration
			if( $_POST['expYear'] == date('Y') && $_POST['expMonth'] <= date('m') ) {
				push_client_error( 'The card you are attempting to deposit with has expired. Please check the expiration date of the card and try again.' );
				return FALSE;
			}
			# Check that we're not depositing into a demo account
			if( isset( $_SESSION['ias_customer'] ) && isset( $_SESSION['ias_customer']->valid ) && $_SESSION['ias_customer']->valid == TRUE ) {
				if( $_SESSION['ias_customer']->isDemo == 1 ) {
					push_client_error( 'You are not allowed to deposit real funds into a demo account.' );
					return FALSE;
				}
			}
			else {
				push_client_error( 'There was an error making a deposit. Please try again.' );
				return FALSE;
			}
			# Risk (minfraud)
			$bincheck = ias_cc_fraud_api::bin_check( $_POST['ccNumber'] , $_POST['amount'] );
			if( isset($bincheck['riskScore']) && $bincheck['riskScore'] > 50 ) {
				push_client_error( 'To continue with this transaction, please contact ' . $wpdb->get_var( ias_fix_db_prefix( "SELECT `name` FROM `{{ias}}brands` WHERE `id` = '" . $_SESSION['ias_customer']->brand_id . "'" ) ) . ' customer support.' );
				ias_customer_notes_api::note( $_SESSION['ias_customer']->id , $_SESSION['ias_customer']->brand_id, 'The customer\'s attempted deposit was denied because the risk score was more than 50%.', 'Denied Deposit Attempt');
				return FALSE;
			}
			// Continue making the deposit!
			ias_so_deposit_api::deposit( $_POST );
			// note
			$bincheck['bin'] = substr( $_POST['ccNumber'] , 0 , 6 );
			$bincheck['last4'] = substr( $_POST['ccNumber'] , -4 );
			$deposit_note = 'A deposit was attempted with the following information:' . "\r\n";
			$deposit_note .= print_r( $bincheck , TRUE );
			ias_customer_notes_api::note( $_SESSION['ias_customer']->id , $_SESSION['ias_customer']->brand_id, $deposit_note , 'Attempted Deposit Information');
		}

		private static function luhn_check($number) {
		  $number=preg_replace('/\D/', '', $number);
		  $number_length=strlen($number);
		  $parity=$number_length % 2;
		  $total=0;
		  for ($i=0; $i<$number_length; $i++) {
		    $digit=$number[$i];
		    if ($i % 2 == $parity) {
		      $digit*=2;
		      if ($digit > 9) {
		        $digit-=9;
		      }
		    }
		    $total+=$digit;
		  }
		  return ($total % 10 == 0) ? TRUE : FALSE;
		}
 } // end class ias_deposit_form
?>