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
		$fields = array(
			'email' => array(
				'type' => 'email',
				'name' => '',
				'label' => '',
				'placeholder' => '',
				'attributes' => array(
						'required' => 'required',
					),
				'value' => '',
				),
			'password' => array(
				'type' => 'password',
				'name' => '',
				'label' => '',
				'placeholder' => '',
				'attributes' => array(
						'required' => 'required',
					),
				'value' => '',
				),
			'brand' => array(
				'type' => 'select',
				'name' => '',
				'label' => '',
				'placeholder' => '',
				'attributes' => array(
						'required' => 'required',
					),
				'value' => '',
				),
			'submit' => array(
				'type' => 'submit',
				'name' => '',
				'label' => '',
				'placeholder' => '',
				'attributes' => array(
						'required' => 'required',
					),
				'value' => '',
				),
		);
	}

	public static function widget( $layout = NULL ) {
		
	}

	public static function shortcode( $layout = NULL ) {
		
	}
} // end of ias_login_form class
?>