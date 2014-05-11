<?php
/**
 * Registration form class for IAS
 */
 class ias_registration_form extends ias_forms {
	private $defaultLayout = array(
			array( 'action' , 'a_aid' , 'a_bid' , 'a_cid' , 'tracker' , 'regIP'),
			array( 'fName' ),
			array( 'lName' ),
			array( 'email' ),
			array( 'phone' ),
			array( 'country' ),
			array( 'currency' ),
			array( 'password' ),
			array( 'repeatPassword' ),
			array( 'brand' ),
			array( 'submit' ),
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
				'value' => 'registerCustomer',
				'validate' => FALSE,
				),
			'a_aid' => array(
				'type' => 'hidden',
				'name' => 'a_aid',
				'label' => NULL,
				'placeholder' => NULL,
				'attributes' => array(),
				'value' => $_SESSION['ias_tracking']->a_aid,
				'validate' => FALSE,
				),
			'a_bid' => array(
				'type' => 'hidden',
				'name' => 'a_bid',
				'label' => NULL,
				'placeholder' => NULL,
				'attributes' => array(),
				'value' => $_SESSION['ias_tracking']->a_bid,
				'validate' => FALSE,
				),
			'a_cid' => array(
				'type' => 'hidden',
				'name' => 'a_cid',
				'label' => NULL,
				'placeholder' => NULL,
				'attributes' => array(),
				'value' => $_SESSION['ias_tracking']->a_cid,
				'validate' => FALSE,
				),
			'tracker' => array(
				'type' => 'hidden',
				'name' => 'tracker',
				'label' => NULL,
				'placeholder' => NULL,
				'attributes' => array(),
				'value' => $_SESSION['ias_tracking']->tracker,
				'validate' => FALSE,
				),
			'regIP' => array(
				'type' => 'hidden',
				'name' => 'regIP',
				'label' => NULL,
				'placeholder' => NULL,
				'attributes' => array(),
				'value' => $_SESSION['ip'],
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
				'value' => ( isset($fName) ) ? $fName : NULL,
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
				'value' => ( isset($lName) ) ? $lName : NULL,
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
				'value' => ( isset($email) ) ? $email : NULL,
				'validate' => array(
						'rules' => array(
								'email' => TRUE,
							),
						'messages' => array(
								'email' => 'Please enter your account email to continue',
							),
					),
				),
			'confirmEmail' => array(
				'type' => 'email',
				'name' => 'confirmEmail',
				'label' => 'Confirm Email',
				'placeholder' => 'Confirm Email',
				'attributes' => array(
						'required' => 'required',
					),
				'value' => NULL,
				'validate' => array(
						'rules' => array(
								'email' => TRUE,
								'equalTo' => '{id}email',
							),
						'messages' => array(
								'email' => 'Please enter your account email to continue',
								'equalTo' => 'Please make sure that the email addresses you have entered match.',
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
				'value' => ( isset($phone) ) ? $phone : NULL,
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
			'cellphone' => array(
				'type' => 'tel',
				'name' => 'cellphone',
				'label' => 'Mobile Phone',
				'placeholder' => 'Mobile Phone',
				'attributes' => array(
						'required' => 'required',
					),
				'value' => ( isset($cellphone) ) ? $cellphone : NULL,
				'validate' => array(
						'rules' => array(
								'digits' => TRUE,
								'cellPhoneVal' => TRUE,
							),
						'messages' => array(
								'digits' => 'Please enter only digits ( 0 - 9 ) with no + or -',
								'cellPhoneVal' => 'Please enter a valid mobile phone number',
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
				'value' => $wpdb->get_results( ias_fix_db_prefix( "SELECT  `{{ias}}countries`.`id` as `value`,  `{{ias}}countries`.`name`,  `{{ias}}countries`.`prefix`, `{{ias}}countries`.`ISO` as `iso`,  `{{ias}}countries`.`region`, `{{ias}}countries`.`currency`  FROM `{{ias}}countries` LEFT JOIN `{{ias}}regions` ON `{{ias}}countries`.`region` = `{{ias}}regions`.`id` WHERE  `{{ias}}countries`.`id` NOT LIKE '0' AND `{{ias}}regions`.`brands` NOT LIKE '[]'" ), ARRAY_A),
				'default' => ( isset($country) ) ? $country : $_SESSION['ias_geoip']->spotid,
				'validate' => FALSE,
				),
			'currency' => array(
				'type' => 'select',
				'name' => 'currency',
				'label' => 'Currency',
				'placeholder' => 'Currency',
				'attributes' => array(
						'required' => 'required',
					),
				'value' => array(
						array(
							'name' => 'US Dollar ( $ )',
							'value' => 'usd',
							),
						array(
							'name' => 'Euro ( € )',
							'value' => 'eur',
							),
						array(
							'name' => 'Pound Sterling ( £ )',
							'value' => 'gbp',
							),
						//array(
						//	'name' => 'Chinese Yuan ( ¥ )',
						//	'value' => 'cny',
						//	),
					),
				'default' => ( isset($currency) ) ? $currency : NULL,
				'validate' => FALSE,
				),
			'password' => array(
				'type' => 'password',
				'name' => 'password',
				'label' => 'Choose Password',
				'placeholder' => 'Choose Password',
				'attributes' => array(
						'required' => 'required',
					),
				'value' => ( isset($password) ) ? $password : NULL,
				'validate' => array(
						'rules' => array(
								'minlength' => 6,
							),
						'messages' => array(
								'minlength' => 'Your password is at least {0} characters long',
							),
					),
				),
			'repeatPassword' => array(
				'type' => 'password',
				'name' => 'repeatPassword',
				'label' => 'Repeat Password',
				'placeholder' => 'Repeat Password',
				'attributes' => array(
						'required' => 'required',
					),
				'value' => '',
				'validate' => array(
						'rules' => array(
								'minlength' => 6,
								'equalTo' => '{id}password',
							),
						'messages' => array(
								'minlength' => 'Your password is at least {0} characters long',
								'equalTo' => 'Your password must match',
							),
					),
				),
			'brand' => array(
				'type' => 'select',
				'name' => 'brand',
				'label' => 'Broker',
				'placeholder' => 'Broker',
				'attributes' => array(
						'required' => 'required',
					),
				'value' => $wpdb->get_results( ias_fix_db_prefix( "SELECT `id` as `value`, `name` , `URL` FROM `{{ias}}brands` WHERE ( `active` = 1 AND  `isBDB` = 1  AND  `licenseKey` NOT LIKE '' ) OR (   `active` = 1 AND  `isBDB` = 0 AND  `apiURL` NOT LIKE ''  AND  `apiUSER` NOT LIKE ''  AND  `apiPass` NOT LIKE '' )" ), ARRAY_A),
				'default' => ( isset($brand) ) ? $brand : NULL,
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
		// get the hidden text areas with the json for the brokers list //
		$header_html = '';
		$regions = $wpdb->get_results( ias_fix_db_prefix( "SELECT `id`,`brands` FROM `{{ias}}regions` WHERE `brands` NOT LIKE '[]'" ), ARRAY_A);
		foreach ($regions as $region) {
			$header_html .= '<textarea readonly="readonly" disabled="disabled" id="{id}_region_' . $region['id'] . '_brands" style="display:none !important;">';
			$brands_array = json_decode($region['brands'],true);
			$brands_select_array = array();
			foreach ($brands_array as $brand) {
				$brand_info = $wpdb->get_row( ias_fix_db_prefix( "SELECT `id` AS `value`, `name`,`URL` as `url` FROM `{{ias}}brands` WHERE `id` = '" . $brand . "'") , ARRAY_A);
				array_push($brands_select_array, $brand_info );
			}
			$header_html .= json_encode($brands_select_array);
			$header_html .= '</textarea>' . "\r\n";
		}
		$this->update_form_head_html( $header_html );
		$footer_js = 'jQuery(function(){jQuery("input[type = \'tel\']").on("keyup",function(){return numbersonly(this,event)});jQuery("input[type = \'tel\']").on("keypress",function(){return numbersonly(this,event)})})' . "\r\n";
		$footer_js .= 'jQuery(function() {' . "\r\n";
		$footer_js .= '	region = jQuery("#{id}_country option:selected").attr(\'data-region\');' . "\r\n";
		$footer_js .= '	content = jQuery("#{id}_region_" + region +"_brands").html();' . "\r\n";
		$footer_js .= '	brands_list_to_select( content , "{id}_brand");' . "\r\n";
		$footer_js .= '	jQuery("#{id}_country").on(\'change\',function() {' . "\r\n";
		$footer_js .= '		var id = jQuery(this).attr(\'id\');' . "\r\n";
		$footer_js .= '		region = jQuery("#" + id + " option:selected").attr(\'data-region\');' . "\r\n";
		$footer_js .= '		content = jQuery("#{id}_region_" + region +"_brands").html();' . "\r\n";
		$footer_js .= '		brands_list_to_select( content , "{id}_brand");' . "\r\n";
		$footer_js .= '	});' . "\r\n";
		$footer_js .= '});' . "\r\n";
		$footer_js .= 'jQuery(function() {' . "\r\n";
		$footer_js .= '	currency = jQuery("#{id}_country option:selected").attr(\'data-currency\');' . "\r\n";
		$footer_js .= '	jQuery("#{id}_currency").val(currency);' . "\r\n";
		$footer_js .= '	jQuery("#{id}_currency").trigger(\'chosen:updated\')' . "\r\n";
		$footer_js .= '	jQuery("#{id}_country").on(\'change\',function() {' . "\r\n";
		$footer_js .= '		var id = jQuery(this).attr(\'id\');' . "\r\n";
		$footer_js .= '		currency = jQuery("#" + id + " option:selected").attr(\'data-currency\');' . "\r\n";
		$footer_js .= '		jQuery("#{id}_currency").val(currency);' . "\r\n";
		$footer_js .= '		jQuery("#{id}_currency").trigger(\'chosen:updated\')' . "\r\n";
		$footer_js .= '	});' . "\r\n";
		$footer_js .= '});' . "\r\n";
		$this->update_form_foot_js( $footer_js );
		$this->get_form_html();
	}

	public static function shortcode( $atts, $content = NULL ) {
		$class = get_class();
		$req_fields = array(
			'a_aid',
			'a_bid',
			'a_cid',
			'tracker',
			'regIP',
			'action',
			'fName',
			'lName',
			'email',
			'phone',
			'country',
			'currency',
			'password',
			'brand',
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
		if(!isset($_SESSION['ias_customer']) || $_SESSION['ias_customer']->valid == FALSE ) {
			return $form->html;
		}
		else {
			$html = do_action('ias_logged_in_form');
			$html .= '<form action="" method="POST" accept-charset="UTF-8" autocomplete="off" enctype="application/x-www-form-urlencoded" target="_self">';
			$html .= '	<input type="hidden" name="action" value="logout" />';
			$html .= '	<input type="hidden" name="form_id" value="none" />';
			$html .= '	<input type="hidden" name="form_none_nonce" value="0" />';
			$html .= '	<input type="submit" class="btn btn-success btn-block button" value="' . __('Log Out',IAS_TEXTDOMAIN) . '" />';
			$html .= '</form>';
			return $html;
		}
	}

	public static function action() {
		global $ias_session, $wpdb;
		$errors = array();
		$req_fields = array(
			'fName' => 'You are missing your First Name. Please enter your First Name and try again.',
			'lName' => 'You are missing your Last Name. Please enter your Last Name and try again.',
			'email' => 'You are missing an email address. Please enter an email address and try again.',
			'phone' => 'You are missing a phone number. Please enter a phone number and try again.',
			'country' => 'You have not chosen a country. Please choose a country and try again.',
			'currency' => 'You have not chosen a currency. Please choose a currency and try again.',
			'password' => 'You have not chosen a password. Please choose a password and try again.',
			'brand' => 'You have not chosen a broker. Please choose a broker and try again.',
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
		if( isset( $_POST['cellphone'] ) ) {
			$post_cellphone_lib = $phoneUtil->parse( $_POST['cellphone'] , $iso );
			if( $phoneUtil->isPossibleNumber( $post_cellphone_lib ) == FALSE ) {
				push_client_error( 'The mobile phone number you have entered is invalid. Please check your details and try again.' );
				return FALSE;
			}
		}
		if( isset( $_POST['confirmEmail']) && $_POST['email'] != $_POST['confirmEmail'] ) {
			push_client_error( 'The confirmation of your email address does not match your email address. Please check the confirmation of your email address and try again.' );
			return FALSE;
		}
		if( isset( $_POST['repeatPassword']) && $_POST['password'] != $_POST['repeatPassword'] ) {
			push_client_error( 'Please check that the password that you have entered matches your confirmation and try again.' );
			return FALSE;
		}
		if( !current_user_can( 'manage_options' ) ) {
			$chosen_region = $wpdb->get_var( ias_fix_db_prefix("SELECT `region` FROM `{{ias}}countries` WHERE `id` = " . $_POST['country'] . " ") );
			$current_region = $_SESSION['ias_geoip']->region;
			if( $chosen_region != $current_region && $current_region == 3 ) {
				push_client_error( 'The country you are trying to register from does not match your current location. Please try again from your country of residence.' );
				return FALSE;
			}
		}
		if( !current_user_can( 'manage_options' ) ) {
			$chosen_region = $wpdb->get_var( ias_fix_db_prefix("SELECT `region` FROM `{{ias}}countries` WHERE `id` = " . $_POST['country'] . " ") );
			$brands_json = $wpdb->get_var( ias_fix_db_prefix("SELECT `brands` FROM `{{ias}}regions` WHERE `id` = " . $chosen_region . " ") );
			try {
				$brands = json_decode($brands_json,true);
			}
			catch (exception $e) {
				push_client_error( 'The country you are attempting to register from cannot register with this broker. Please choose another broker and try again.' );
				return FALSE;
			}
		}
		// now let's try to register with spot
		$spot_reg_customer_array = array(
			'MODULE' => 'Customer',
			'COMMAND' => 'add',
			'FirstName' => $_POST['fName'],
			'LastName' => $_POST['lName'],
			'email' => $_POST['email'],
			'Phone' => $_POST['phone'],
			'gender' => 'male',
			'Country' => $_POST['country'],
			'birthday' => '1970-01-01',
			'campaignId' => $wpdb->get_var( ias_fix_db_prefix("SELECT `campaignID` FROM `{{ias}}brands` WHERE `id` = '" . $_POST['brand'] . "'") ),
			'subCampaign' => ( !is_null( $_SESSION['ias_tracking']->tracker ) ) ? $_SESSION['ias_tracking']->tracker : '',
			'a_aid' => ( !is_null( $_SESSION['ias_tracking']->a_aid ) ) ? $_SESSION['ias_tracking']->a_aid : '',
			'a_bid' => ( !is_null( $_SESSION['ias_tracking']->a_bid ) ) ? $_SESSION['ias_tracking']->a_bid : '',
			'a_cid' => ( !is_null( $_SESSION['ias_tracking']->a_cid ) ) ? $_SESSION['ias_tracking']->a_cid : '',
			'siteLanguage' => substr( get_bloginfo('language') , 0 , 2 ),
			'currency' => strtoupper( $_POST['currency'] ),
			'password' => $_POST['password'],
		);
		if( !current_user_can( 'manage_options' ) ) {
			$spot_reg_customer_array['registrationCountry'] = $_SESSION['ias_geoip']->spotid;
		} else {
			$spot_reg_customer_array['registrationCountry'] = $_POST['country'];
		}
		if( strlen( $_SESSION['ias_geoip']->omni['region_code'] ) > 0 && strlen( $_SESSION['ias_geoip']->omni['region_code'] ) <= 2 ) {
			$spot_reg_customer_array['State'] = $_SESSION['ias_geoip']->omni['region_code'];
		}
		if( strlen( $_SESSION['ias_geoip']->omni['city_name'] ) > 0 ) {
			$spot_reg_customer_array['City'] = $_SESSION['ias_geoip']->omni['city_name'];
		}
		if( isset( $_POST['cellphone'] ) && strlen( $_POST['cellphone'] ) != 0 ) {
			$spot_reg_customer_array['cellphone'] = $_POST['cellphone'];
		}
		if( $_POST['brand'] == 1 ) {
			$spot_reg_customer_array['regulateStatus'] = 'pending';
			$spot_reg_customer_array['regulateType'] = '1';
		}

		$errors = array(
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
			'invalidRegulateStatus' => 'There was a general issue with your registration. Please try again later.',
			'invalidRegulateType' => 'There was a general issue with your registration. Please try again later.',
		);

		$reg_results = ias_so_api::return_query( $_POST['brand'] , $spot_reg_customer_array );
		if(!isset($reg_results['operation_status']) || !isset($reg_results['connection_status'])) {
			push_client_error( 'The broker that you have chosen is not currently available for registration. Please choose another broker and try again.' );
			$bug_report = 'SpotAPI has returned an error on an attempted customer registration.' . "\r\n" . "\r\n" . '*Attempted Query*:' . "\r\n" . '<pre>' . "\r\n";
			$bug_report .= print_r($spot_reg_customer_array,true) . "\r\n";
			$bug_report .= '</pre>' . "\r\n". "\r\n";
			$bug_report .= '*SpotAPI Return*' . "\r\n" . "\r\n";
			$bug_report .= '<pre>' . "\r\n";
			$bug_report .= print_r($reg_results,true) . "\r\n";
			$bug_report .= '</pre>' . "\r\n". "\r\n";
			report_ias_bug( 'Registration API Failure from ' . get_bloginfo('wpurl') , $bug_report );
		}
		else if( $reg_results['connection_status'] != 'successful' || $reg_results['operation_status'] != 'successful') {
			switch (TRUE) {
				case ($reg_results['connection_status'] != 'successful'):
					push_client_error( 'The broker that you have chosen is not currently available for registration. Please choose another broker and try again.' );
					break;

				case(!is_array($reg_results['errors']['error'])):
					foreach ($reg_results['errors'] as $key => $error) {
						if(isset($errors[$error])) {
							push_client_error( $errors[$error] );
						} else {
							push_client_error( 'There was a general issue performing this action. Please try again later' );
						}
					}
					break;
				
				default:
					foreach ($reg_results['errors']['error'] as $error) {
						if(isset($errors[$error])) {
							push_client_error( $errors[$error] );
						} else {
							push_client_error( 'There was a general issue performing this action. Please try again later' );
						}
					}
					break;
			}
			$bug_report = 'SpotAPI has returned an error on an attempted customer registration.' . "\r\n" . "\r\n" . '*Attempted Query*:' . "\r\n" . '<pre>' . "\r\n";
			$bug_report .= print_r($spot_reg_customer_array,true) . "\r\n";
			$bug_report .= '</pre>' . "\r\n". "\r\n";
			$bug_report .= '*SpotAPI Return*' . "\r\n" . "\r\n";
			$bug_report .= '<pre>' . "\r\n";
			$bug_report .= print_r($reg_results,true) . "\r\n";
			$bug_report .= '</pre>' . "\r\n". "\r\n";
			report_ias_bug( 'Registration API Failure from ' . get_bloginfo('wpurl') , $bug_report );
		}
		if( !isset( $reg_results['Customer']['id'] ) ) {
			push_client_error( 'There was an issue processing your registration. Please try again.' );
			return FALSE;
		}
		ias_customer::just_registered( $_POST['brand'] , $reg_results );
		$customer_id = $reg_results['Customer']['id'];
		ias_tracking::tracking_trigger('customerGen');
	}

	public static function widget( $args , $instance ) {
		$class = get_class();
		$form = new $class();
		$possible_atts = array(
			'use_chosen' => 'use_chosen',
			'use_validate' => 'use_validate',
			'use_captcha' => 'use_captcha',
			'response_size' => 'set_response_size',
			'header_html' => 'update_form_head_html',
			'header_css' => 'update_form_head_css',
			'header_js' => 'update_form_head_js',
			'footer_html' => 'update_form_foot_html',
			'footer_css' => 'update_form_foot_css',
			'footer_js' => 'update_form_foot_js',
			'form_action' => 'set_form_action',
			'form_method' => 'set_form_method',
			'form_charset' => 'set_form_charset',
			'form_autocomplete' => 'set_form_autocomplete',
			'form_enctype' => 'set_form_enctype',
			'form_target' => 'set_form_target',
			'form_attributes' => 'add_form_attributes',
		);
		foreach ($instance as $key => $value) {
			if(isset($possible_atts[$key]) && $key !== 'use_chosen' && $key !== 'use_validate' ) {
				$function = $possible_atts[$key];
				$form->$function( $value );
			}
			else if ( isset($possible_atts[$key]) && $key == 'use_chosen' ) {
				if( $value == 1 ) {
					$form->use_chosen( TRUE );
				} else {
					$form->use_chosen( FALSE );
				}
			}
			else if ( isset($possible_atts[$key]) && $key == 'use_validate' ) {
				if( $value == 1 ) {
					$form->use_validate( TRUE );
				} else {
					$form->use_validate( FALSE );
				}
			}
		}
		$form->regenerate();
		if(!isset($_SESSION['ias_customer']) || $_SESSION['ias_customer']->valid == FALSE ) {
			return $form->html;
		}
		else {
			$html = do_action('ias_logged_in_form');
			$html .= '<form action="" method="POST" accept-charset="UTF-8" autocomplete="off" enctype="application/x-www-form-urlencoded" target="_self">';
			$html .= '	<input type="hidden" name="action" value="logout" />';
			$html .= '	<input type="hidden" name="form_id" value="none" />';
			$html .= '	<input type="hidden" name="form_none_nonce" value="0" />';
			$html .= '	<input type="submit" class="btn btn-success btn-block button" value="' . __('Log Out',IAS_TEXTDOMAIN) . '" />';
			$html .= '</form>';
			return $html;
		}
	}
 } // end of ias_registration_form

 class ias_registration_form_widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'ias_registration_form_widget',
			__('IAS Registration Form', IAS_TEXTDOMAIN),
			array( 
				'description' => __( 'Allows a user register a trading account with a chosen broker.', IAS_TEXTDOMAIN ), 
			)
		);
	}

	public function widget( $args, $instance ) {
		print( ias_registration_form::widget( $args , $instance ) );
	}

	public function form( $instance ) {
		$html = '<div style="min-height: 200px; margin-bottom: 20px;">' . "\r\n";
		$fields = array(
			'form_action' => array(
				'type' => 'text',
				'name' => $this->get_field_name( 'form_action' ),
				'label' => 'Form Action',
				'placeholder' => 'Form Action',
				'id' => $this->get_field_id( 'form_action' ),
				'value' => ( isset( $instance['form_action'] ) ) ? $instance['form_action'] : NULL,
				'attributes' => NULL,
				),
			'form_method' => array(
				'type' => 'text',
				'name' => $this->get_field_name( 'form_method' ),
				'label' => 'Form Method',
				'placeholder' => 'Form Method',
				'id' => $this->get_field_id( 'form_method' ),
				'value' => ( isset( $instance['form_method'] ) ) ? $instance['form_method'] : 'POST',
				'attributes' => NULL,
				),
			'form_charset' => array(
				'type' => 'text',
				'name' => $this->get_field_name( 'form_charset' ),
				'label' => 'Form Character Set',
				'placeholder' => 'Form Character Set',
				'id' => $this->get_field_id( 'form_charset' ),
				'value' => ( isset( $instance['form_charset'] ) ) ? $instance['form_charset'] : 'UTF-8',
				'attributes' => NULL,
				),
			'form_autocomplete' => array(
				'type' => 'checkbox',
				'name' => $this->get_field_name( 'form_autocomplete' ),
				'label' => 'Form autocompletes',
				'placeholder' => 'Form autocompletes',
				'id' => $this->get_field_id( 'form_autocomplete' ),
				'value' => ( isset( $instance['form_autocomplete'] ) ) ? $instance['form_autocomplete'] : FALSE,
				'attributes' => NULL,
				),
			'form_enctype' => array(
				'type' => 'text',
				'name' => $this->get_field_name( 'form_enctype' ),
				'label' => 'Form Encoding Type',
				'placeholder' => 'Form Encoding Type',
				'id' => $this->get_field_id( 'form_enctype' ),
				'value' => ( isset( $instance['form_enctype'] ) ) ? $instance['form_enctype'] : 'application/x-www-form-urlencoded',
				'attributes' => NULL,
				),
			'form_target' => array(
				'type' => 'text',
				'name' => $this->get_field_name( 'form_target' ),
				'label' => 'Target Window',
				'placeholder' => 'Target Window',
				'id' => $this->get_field_id( 'form_target' ),
				'value' => ( isset( $instance['form_target'] ) ) ? $instance['form_target'] : '_self',
				'attributes' => NULL,
				),
			'use_chosen' => array(
				'type' => 'checkbox',
				'name' => $this->get_field_name( 'use_chosen' ),
				'label' => 'Use Chosen',
				'placeholder' => 'Use Chosen',
				'id' => $this->get_field_id( 'use_chosen' ),
				'value' => ( isset( $instance['use_chosen'] ) ) ? $instance['use_chosen'] : TRUE,
				'attributes' => NULL,
				),
			'use_validate' => array(
				'type' => 'checkbox',
				'name' => $this->get_field_name( 'use_validate' ),
				'label' => 'Use jQuery Validate',
				'placeholder' => 'Use jQuery Validate',
				'id' => $this->get_field_id( 'use_validate' ),
				'value' => ( isset( $instance['use_validate'] ) ) ? $instance['use_validate'] : TRUE,
				'attributes' => NULL,
				),
			'responsive_size' => array(
				'type' => 'text',
				'name' => $this->get_field_name( 'responsive_size' ),
				'label' => 'Responsive Size',
				'placeholder' => 'Responsive Size',
				'id' => $this->get_field_id( 'responsive_size' ),
				'value' => ( isset( $instance['responsive_size'] ) ) ? $instance['responsive_size'] : 'sm',
				'attributes' => NULL,
				),
		);
		$form = new ias_widget_form( $fields );
		$html .= $form->html;
		$html .= '</div>' . "\r\n";
		print( $html );
	}

	public function update( $new_instance, $old_instance ) {
		foreach ($new_instance as $key => $value) {
			$old_instance[$key] = $value;
		}
		return $old_instance;
	}
}
?>