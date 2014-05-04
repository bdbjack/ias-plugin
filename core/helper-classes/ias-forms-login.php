<?php
/**
 * IAS Login Form Class
 */

class ias_login_form extends ias_forms {

	private $defaultLayout = array(
		array('email'),
		array('password'),
		array('brand'),
		array('submit'),
	);
	
	function __construct( $layout = NULL ) {
		global $ias_session, $wpdb;
		if(isset($ias_session['perma_get']['email'])) {
			$email = $ias_session['perma_get']['email'];
		}
		else {
			$email = NULL;
		}
		$fields = array(
			'email' => array(
				'type' => 'email',
				'name' => 'email',
				'label' => 'Login Email',
				'placeholder' => 'Login Email',
				'attributes' => array(
						'required' => 'required',
					),
				'value' => $email,
				'validate' => array(
						'rules' => array(
								'email' => TRUE,
							),
						'messages' => array(
								'email' => 'Please enter your account email to continue',
							),
					),
				),
			'password' => array(
				'type' => 'password',
				'name' => 'password',
				'label' => 'Login Password',
				'placeholder' => 'Login Password',
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
			'brand' => array(
				'type' => 'select',
				'name' => 'brand',
				'label' => 'Broker',
				'placeholder' => 'Broker',
				'attributes' => array(
						'required' => 'required',
					),
				'value' => $wpdb->get_results( ias_fix_db_prefix( "SELECT `name` as `text` FROM `{{ias}}brands` WHERE (  `active` = 1 AND  `isBDB` = 1  AND  `licenseKey` NOT LIKE '' ) OR (   `active` = 1 AND  `isBDB` = 0 AND  `apiURL` NOT LIKE ''  AND  `apiUSER` NOT LIKE ''  AND  `apiPass` NOT LIKE '' )" ), ARRAY_A),
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
				'value' => 'Log In',
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

	public static function widget( $layout = NULL ) {
		
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
		if( !is_null( $content ) ) {
			$form->update_form_head_html( $content );
			$form->regenerate();
		}
		if(isset($atts['debug'])) {
			return htmlentities($form->html);
		}
		else if ( isset($atts['fulldebug'] ) ) {
			return htmlentities( print_r( $form , true ) );
		}
		else {
			return $form->html;
		}
		
	}
} // end of ias_login_form class
?>