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
		if(isset($_SESSION['perma_get']['email'])) {
			$email = $_SESSION['perma_get']['email'];
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

	public static function action() {
		global $ias_session;
		$cust = ias_customer::login_validate( $_POST['brand'] , $_POST['email'] , $_POST['password'] );
		if( $cust->valid == TRUE ) {
			$_SESSION['ias_customer'] = $cust;
			$ias_session['ias_customer'] = $cust;
		}
		else {
			unset($_SESSION['ias_customer']);
			unset($ias_session['ias_customer']);
		}
	}

	public static function logout() {
		global $ias_session;
		unset($_SESSION['ias_customer']);
		unset($ias_session['ias_customer']);
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