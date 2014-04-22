<?php
/*
Plugin Name: Instant Affiliate Software Base
Plugin URI: http://rm.14all.me/projects/instant-affiliate-software-ias-plugin
Description: The Instant Affiliate Software WordPress plugin is a plugin for WordPress which allows an affiliate to hook up instantly to a broker.
Author: 3 Musketeers Group
Version: 0.01
Author URI: http://rm.14all.me/projects/instant-affiliate-software-ias-plugin
Text Domain: ias
*/

/**
 * Let's Define some never-changing content
 */
if (!defined('PHP_VERSION_ID')) {
    $version = explode('.', PHP_VERSION);

    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}

if (!defined('IAS_BASE')) {
    define('IAS_BASE', dirname( __FILE__ ) );
}

if (!defined('IAS_URL_BASE')) {
    define('IAS_URL_BASE', plugin_dir_url( __FILE__ ) );
}

if (!defined('IAS_TEXTDOMAIN')) {
    define('IAS_TEXTDOMAIN', 'ias');
}

if (!defined('IAS_SHOW_ERRORS')) {
    define('IAS_SHOW_ERRORS', TRUE);
}

if (!defined('IAS_DB_VERSION')) {
    define('IAS_DB_VERSION', 0.01);
}

/**
 * Set up Text Domain & Translation Directories
 */
load_plugin_textdomain(IAS_TEXTDOMAIN, false, IAS_BASE . '/languages' );

/**
 * Set up custom error handling
 */
function ias_error_handling($errno, $errstr, $errfile, $errline) {
	if (!(error_reporting() & $errno)) {
        return;
    }
    switch ($errno) {
    case E_USER_ERROR:
        $error = new WP_Error('ias_error', __("$errstr error in file $errfile on line $errline", IAS_TEXTDOMAIN));
        exit(1);
        break;

    case E_USER_WARNING:
        $error = new WP_Error('ias_warning', __("$errstr error in file $errfile on line $errline", IAS_TEXTDOMAIN));
        break;

    case E_USER_NOTICE:
        $error = new WP_Error('ias_notice', __("$errstr error in file $errfile on line $errline", IAS_TEXTDOMAIN));
        break;

    default:
        $error = new WP_Error('ias_unknown', __("$errstr error in file $errfile on line $errline", IAS_TEXTDOMAIN));
        break;
    }
    if(IAS_SHOW_ERRORS == TRUE) {
		ias_show_admin_error($error->get_error_message() , 'error');
    }
    return true;
}

$error_handling = set_error_handler("ias_error_handling");

/**
 * Set up error message displays
 */
$ias_error_messages = array();
$ias_warning_messages = array();
$ias_sticky_messages = array();
$ias_client_messages = array();
$ias_install_valid = true;

/**
 * In order to faciliate faster development, we will bypass the need to check the entire site against a license, and will simply rely on the API licensing server for individual brands
 * This will need to change in the Version 2.0 release
 */
$ias_license = true;

function ias_show_admin_error( $message , $type ) {
	print('<div class="' . $type . '">');
 	print('<p>' . __( $message , IAS_TEXTDOMAIN ) . '</p>');
 	print('</div>');
}

function ias_add_error( $message , $type = 'error' ) {
	global $ias_error_messages,$ias_warning_messages,$ias_sticky_messages,$ias_install_valid;
	switch ($type) {
		case 'update':
			array_push($ias_warning_messages, $message);
			break;

		case 'sticky':
			array_push($ias_sticky_messages, $message);
			break;
		
		default:
			array_push($ias_error_messages, $message);
			break;
	}
}

/**
 * Set up Password Hashing
 */
require_once( ABSPATH . 'wp-includes/class-phpass.php');
$wp_hasher = new PasswordHash(16, FALSE);

/**
 * Check to make sure that we have core files
 */
if(!file_exists( IAS_BASE . '/core/core.php') ) {
	array_push($ias_error_messages, 'The core.php file does not exist. Please make sure that the entire plugin has been uploaded.');
	$ias_install_valid = false;
} else {
	require_once(IAS_BASE . '/core/core.php');
}


add_action('admin_notices','ias_show_admin_notices');

register_activation_hook(__FILE__,'ias_activation');

/**
 * Load all of the files under the "functions" sub-directory
 */
$dir = new RecursiveDirectoryIterator(IAS_BASE . '/functions');
$iterator = new RecursiveIteratorIterator($dir);
$functionsobj = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
foreach ($functionsobj as $name => $obj) {
	if(strpos($name, 'index.php') === FALSE) {
		require_once($name);
	}
}

function ias_show_admin_notices() {
	global $ias_error_messages ,$ias_warning_messages ,$ias_sticky_messages ,$ias_client_messages, $wpdb;
	foreach ($ias_error_messages as $message) {
		ias_show_admin_error( $message , 'error' );
	}
	foreach ($ias_warning_messages as $message) {
		ias_show_admin_error( $message , 'updated' );
	}
	foreach ($ias_sticky_messages as $message) {
		ias_show_admin_error( $message , 'update-nag' );
	}
}
?>