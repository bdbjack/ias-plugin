<?php
	$shortcodes = array(
		'ias_debug' => 'ias_debug_shortcode',
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
		$html = '<pre>' . "\r\n";
		$html .= print_r( $_POST , true ) . "\r\n";
		$html .= '</pre>' . "\r\n";
		$html .= '<pre>' . "\r\n";
		$html .= print_r( $_GET , true ) . "\r\n";
		$html .= '</pre>' . "\r\n";
		$html .= '<pre>' . "\r\n";
		$html .= print_r( $_SESSION , true ) . "\r\n";
		$html .= '</pre>' . "\r\n";
		$html .= '<pre>' . "\r\n";
		$html .= print_r( $_SERVER , true ) . "\r\n";
		$html .= '</pre>' . "\r\n";
	return $html;
	}
?>