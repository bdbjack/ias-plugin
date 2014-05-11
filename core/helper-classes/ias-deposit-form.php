<?php
/**
 * Deposit Form Class for IAS
 */
 class ias_deposit_form extends ias_forms {
		private $defaultLayout = array(
			array('action'),
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
					'default' => ( isset($country) ) ? $country : $_SESSION['ias_customer']->country_id,
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
					'validate' => array(
							'rules' => array(
									'required' => TRUE,
								),
							'messages' => array(
									'required' => 'Please enter your billing address.',
								),
						),
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
					'validate' => array(
							'rules' => array(
									'required' => TRUE,
								),
							'messages' => array(
									'required' => 'Please enter your city of residence.',
								),
						),
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
					'validate' => array(
							'rules' => array(
								'required' => TRUE,
							),
							'messages' => array(
								'required' => 'Please enter a valid postal code to continue.',
							),
						),
					),
				'ccNumber' => array(
						'type' => 'tel',
						'name' => 'ccNumber',
						'label' => 'Credit Card Number',
						'placeholder' => 'Credit Card Number',
						'attributes' => array(
								'required' => 'required',
							),
						'value' => '',
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
						'value' => '',
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
						'default' => ( isset($expMonth) ) ? $expMonth : date('m'),
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
						'value' => function() {
							$return = array();
							$current_year = date('y');
							$max_year = $current_year + 20;
							while( $current_year <= $max_year ) {
								$option = array( 
									'value' => $current_year,
									'name' => $current_year,
								);
								$current_year ++;
							}
							return $return;
						},
						'default' => ( isset($expYear) ) ? $expYear : date('y'),
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
			if(!is_null($layout)) {
				$this->update_form_layout( $layout );
			}
			else {
				$this->update_form_layout( $this->defaultLayout );
			}
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
 } // end class ias_deposit_form
?>