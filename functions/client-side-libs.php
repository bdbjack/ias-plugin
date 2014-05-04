<?php
/**
 * All functions for loading client side libraries
 */
function ias_wp_enqueue_scripts() {
	$scripts = array(
		array(
			'handle' => 'jquery',
			'src' => '//cdnjs.cloudflare.com/ajax/libs/jquery/1.11.0/jquery.js',
			'deps' => FALSE,
			'ver' => NULL,
			'in_footer' => FALSE,
		),
		array(
			'handle' => 'chosen',
			'src' => '//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.jquery.min.js',
			'deps' => array('jquery'),
			'ver' => NULL,
			'in_footer' => FALSE,
		),
		array(
			'handle' => 'validate',
			'src' => '//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.11.1/jquery.validate.min.js',
			'deps' => array('jquery'),
			'ver' => NULL,
			'in_footer' => FALSE,
		),
	);
	foreach ($scripts as $script) {
		wp_deregister_script($script['handle']);
		wp_register_script($script['handle'],$script['src'],$script['deps'],$script['ver'],$script['in_footer']);
		wp_enqueue_script($script['handle']);
	}
	ias_wp_enqueue_style();
}

function ias_wp_enqueue_style() {
	$styles = array(
		array(
			'handle' => 'chosen',
			'src' => '//cdnjs.cloudflare.com/ajax/libs/chosen/1.1.0/chosen.css',
			'deps' => FALSE,
			'ver' => NULL,
			'media' => 'all',
		),
	);
	foreach ($styles as $style) {
		wp_deregister_style($style['handle']);
		wp_register_style($style['handle'],$style['src'],$style['deps'],$style['ver'],$style['media']);
		wp_enqueue_style($style['handle']);
	}
}

function ias_admin_enqueue_scripts() {
	$scripts = array(
		array(
			'handle' => 'ace',
			'src' => '//cdnjs.cloudflare.com/ajax/libs/ace/1.1.01/ace.js',
			'deps' => array('jquery'),
			'ver' => NULL,
			'in_footer' => FALSE,
		),
	);
	foreach ($scripts as $script) {
		wp_deregister_script($script['handle']);
		wp_register_script($script['handle'],$script['src'],$script['deps'],$script['ver'],$script['in_footer']);
		wp_enqueue_script($script['handle']);
	}
}
?>