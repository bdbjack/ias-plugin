<?php
	$shortcodes = array(
		'ias_debug' => 'ias_debug_shortcode',
		array(
			'shortcode' => 'ias_login_form',
			'function' => array( 'ias_login_form' , 'shortcode' ),
		),
		array(
			'shortcode' => 'ias_registration_form',
			'function' => array( 'ias_registration_form' , 'shortcode' ),
		),
		array(
			'shortcode' => 'ias_deposit_form',
			'function' => array( 'ias_deposit_form' , 'shortcode' ),
		),
	);
	$shortcodes[] = do_action('ias_add_shortcode');
	foreach ($shortcodes as $key => $value) {
		if(is_array($value)) {
			add_shortcode( $value['shortcode'] , $value['function'] );
		} else {
			add_shortcode( $key , $value );
		}
	}

	function ias_debug_shortcode( $atts, $content = NULL ) {
		global $ias_get, $ias_post, $ias_session;
		$html = '<pre>' . "\r\n";
		$html .= print_r( $ias_get , true ) . "\r\n";
		$html .= '</pre>' . "\r\n";
		$html .= '<pre>' . "\r\n";
		$html .= print_r( $ias_post , true ) . "\r\n";
		$html .= '</pre>' . "\r\n";
		$html .= '<pre>' . "\r\n";
		$html .= print_r( $ias_session , true ) . "\r\n";
		$html .= '</pre>' . "\r\n";
		//$html .= '<pre>' . "\r\n";
		//$html .= print_r( $_SERVER , true ) . "\r\n";
		//$html .= '</pre>' . "\r\n";
	return $html;
	}
?>