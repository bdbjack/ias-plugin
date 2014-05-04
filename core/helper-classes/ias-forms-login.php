<?php
/**
 * IAS Login Form Class
 */

class ias_login_form extends ias_forms {

	private $defaultLayout = array(
		array('action'),
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
			'action' => array(
				'type' => 'hidden',
				'name' => 'action',
				'label' => NULL,
				'placeholder' => NULL,
				'attributes' => array(),
				'value' => 'login',
				'validate' => FALSE,
				),
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

	public static function widget( $args , $instance ) {
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
		$form->regenerate();
		return $form->html;
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

class ias_login_form_widget extends WP_Widget {
	public function __construct() {
		parent::__construct(
			'ias_login_form_widget',
			__('IAS Login Form', IAS_TEXTDOMAIN),
			array( 
				'description' => __( 'Allows a user to log in with their username and password from the intended broker.', IAS_TEXTDOMAIN ), 
			)
		);
	}

	public function widget( $args, $instance ) {
		print( ias_login_form::widget( $args , $instance ) );
	}

	public function form( $instance ) {
		$html = '<div style="min-height: 200px; margin-bottom: 20px;">' . "\r\n";
		$fields = array(
			'header_html' => array(
				'type' => 'textarea',
				'name' => $this->get_field_name( 'header_html' ),
				'label' => 'Header HTML',
				'placeholder' => 'Header HTML',
				'id' => $this->get_field_id( 'header_html' ),
				'value' => ( isset( $instance['header_html'] ) ) ? $instance['header_html'] : NULL,
				'attributes' => NULL,
				),
			'header_css' => array(
				'type' => 'textarea',
				'name' => $this->get_field_name( 'header_css' ),
				'label' => 'Header CSS',
				'placeholder' => 'Header CSS',
				'id' => $this->get_field_id( 'header_css' ),
				'value' => ( isset( $instance['header_css'] ) ) ? $instance['header_css'] : NULL,
				'attributes' => NULL,
				),
			'header_js' => array(
				'type' => 'textarea',
				'name' => $this->get_field_name( 'header_js' ),
				'label' => 'Header JS',
				'placeholder' => 'Header JS',
				'id' => $this->get_field_id( 'header_js' ),
				'value' => ( isset( $instance['header_js'] ) ) ? $instance['header_js'] : NULL,
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
			'footer_html' => array(
				'type' => 'textarea',
				'name' => $this->get_field_name( 'footer_html' ),
				'label' => 'Footer HTML',
				'placeholder' => 'Footer HTML',
				'id' => $this->get_field_id( 'footer_html' ),
				'value' => ( isset( $instance['footer_html'] ) ) ? $instance['footer_html'] : NULL,
				'attributes' => NULL,
				),
			'footer_css' => array(
				'type' => 'textarea',
				'name' => $this->get_field_name( 'footer_css' ),
				'label' => 'Footer CSS',
				'placeholder' => 'Footer CSS',
				'id' => $this->get_field_id( 'footer_css' ),
				'value' => ( isset( $instance['footer_css'] ) ) ? $instance['footer_css'] : NULL,
				'attributes' => NULL,
				),
			'footer_js' => array(
				'type' => 'textarea',
				'name' => $this->get_field_name( 'footer_js' ),
				'label' => 'Footer JS',
				'placeholder' => 'Footer JS',
				'id' => $this->get_field_id( 'footer_js' ),
				'value' => ( isset( $instance['footer_js'] ) ) ? $instance['footer_js'] : NULL,
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