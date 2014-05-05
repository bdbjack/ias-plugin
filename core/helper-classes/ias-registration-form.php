<?php
/**
 * Registration form class for IAS
 */
 class ias_registration_form extends ias_forms {
	private $defaultLayout = array(
			array( 'action' , 'a_aid' , 'a_bid' , 'a_cid' , 'tracker' , 'regIP'),
			array( 'fName' , 'lName' ),
			array( 'email' , 'confirmEmail' ),
			array( 'phone' , 'cellphone' ),
			array( 'country' ),
			array( 'currency' ),
			array( 'password' , 'repeatPassword' ),
			array( 'brand' ),
			array( 'submit' ),
		);

	function __construct( $layout = NULL , $override = array() ) {
		global $ias_session, $wpdb;
		foreach ($_SESSION['perma_get'] as $key => $value) {
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
				'value' => $ias_session['ias_tracking']->a_aid,
				'validate' => FALSE,
				),
			'a_bid' => array(
				'type' => 'hidden',
				'name' => 'a_bid',
				'label' => NULL,
				'placeholder' => NULL,
				'attributes' => array(),
				'value' => $ias_session['ias_tracking']->a_bid,
				'validate' => FALSE,
				),
			'a_cid' => array(
				'type' => 'hidden',
				'name' => 'a_cid',
				'label' => NULL,
				'placeholder' => NULL,
				'attributes' => array(),
				'value' => $ias_session['ias_tracking']->a_cid,
				'validate' => FALSE,
				),
			'tracker' => array(
				'type' => 'hidden',
				'name' => 'tracker',
				'label' => NULL,
				'placeholder' => NULL,
				'attributes' => array(),
				'value' => $ias_session['ias_tracking']->tracker,
				'validate' => FALSE,
				),
			'regIP' => array(
				'type' => 'hidden',
				'name' => 'regIP',
				'label' => NULL,
				'placeholder' => NULL,
				'attributes' => array(),
				'value' => $ias_session['ip'],
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
				// Only Show Countries from regions which can register //
				'value' => $wpdb->get_results( ias_fix_db_prefix( "SELECT  `{{ias}}countries`.`id` as `value`,  `{{ias}}countries`.`name`,  `{{ias}}countries`.`prefix`,  `{{ias}}countries`.`region`  FROM `{{ias}}countries` LEFT JOIN `{{ias}}regions` ON `{{ias}}countries`.`region` = `{{ias}}regions`.`id` WHERE  `{{ias}}countries`.`id` NOT LIKE '0' AND `{{ias}}regions`.`brands` NOT LIKE '[]'" ), ARRAY_A),
				'default' => $_SESSION['ias_geoip']->spotid,
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
						array(
							'name' => 'Chinese Yuan ( ¥ )',
							'value' => 'cny',
							),
					),
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
				'value' => '',
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
		$class = get_class();
		$form = new $class();
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
		if(is_array($atts)) {
			foreach ($atts as $att => $value) {
				if(isset($possible_atts[$att])) {
					$function = $possible_atts[$att];
					$form->$function( $value );
				}
			}
		}
		if(isset($atts['debug'])) {
			return htmlentities($form->html);
		}
		else if ( isset($atts['fulldebug'] ) ) {
			return htmlentities( print_r( $form , true ) );
		}
		else {
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
	}
 } // end of ias_registration_form
?>