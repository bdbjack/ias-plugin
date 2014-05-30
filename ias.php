<?php
/*
Plugin Name: Instant Affiliate Software Base
Description: The Instant Affiliate Software WordPress plugin is a plugin for WordPress which allows an affiliate to hook up instantly to a broker.
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

if (!defined('IAS_PLUGIN_BASENAME')) {
    define('IAS_PLUGIN_BASENAME', plugin_basename( dirname( __FILE__ ) ) );
}

if (!defined('IAS_PLUGIN_BASEFOLDER')) {
    define('IAS_PLUGIN_BASEFOLDER', dirname( __FILE__ ) );
}

if (!defined('IAS_PLUGIN_FILENAME')) {
    define('IAS_PLUGIN_FILENAME', str_replace( IAS_PLUGIN_BASEFOLDER . '/', '', plugin_basename(__FILE__) ) );
}

if (!defined('IAS_TEXTDOMAIN')) {
    define('IAS_TEXTDOMAIN', 'ias');
}

if (!defined('IAS_SHOW_ERRORS')) {
    define('IAS_SHOW_ERRORS', FALSE);
}

if (!defined('IAS_DB_VERSION')) {
    define('IAS_DB_VERSION', 0.01);
}

if (!defined('IAS_VERSION')) {
    define('IAS_VERSION', 'Beta Release');
}

if (!defined('IAS_VERSION_ID')) {
    define('IAS_VERSION_ID', 7);
}

if (!defined('MUSKETEERS_PROJECT_ID')) {
    define('MUSKETEERS_PROJECT_ID', 18);
}

if (!defined('MUSKETEERS_REPO_NAME')) {
    define('MUSKETEERS_REPO_NAME', 'ias-plugin');
}

/**
 * Set up Text Domain & Translation Directories
 */
load_plugin_textdomain(IAS_TEXTDOMAIN, false, IAS_BASE . '/languages' );

/**
 * Set up custom error handling
 */
libxml_use_internal_errors(true);
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
    report_ias_bug( 'Code Error on ' . get_bloginfo('wpurl') , $error->get_error_message() );
    return true;
}

$error_handling = set_error_handler("ias_error_handling");

function killer() {
	throw new Exception( 'Got to here!', 0 );
}

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
 * Load all of the files under the "core/first-load" sub-directory
 */
$first_load_dir = new RecursiveDirectoryIterator(IAS_BASE . '/core/first-load');
$first_load_iterator = new RecursiveIteratorIterator($first_load_dir);
$first_load_obj = new RegexIterator($first_load_iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
foreach ($first_load_obj as $name => $obj) {
	if(strpos($name, 'index.php') === FALSE) {
		require_once($name);
	}
}

/**
 * Load all of the files under the "core/helper-classes" sub-directory
 */
$core_dir = new RecursiveDirectoryIterator(IAS_BASE . '/core/helper-classes');
$core_iterator = new RecursiveIteratorIterator($core_dir);
$core_functionsobj = new RegexIterator($core_iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
foreach ($core_functionsobj as $name => $obj) {
	if(strpos($name, 'index.php') === FALSE) {
		require_once($name);
	}
}

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

/**
 * Load all autoloaders for autoloaded classes
 */
$dir = new RecursiveDirectoryIterator(IAS_BASE . '/autoLoadedClasses');
$iterator = new RecursiveIteratorIterator($dir);
$functionsobj = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);
foreach ($functionsobj as $name => $obj) {
	if(strpos($name, 'autoload.php') !== FALSE) {
		require_once($name);
	}
}

/**
 * Define Info from Superglobals to regular global variables so they can be accessed by the variable variable method
 */

$ias_get = $_GET;
$ias_post = $_POST;
$ias_server = $_SERVER;
$ias_session = array();

/**
 * Run all hooked functions
 */
ias_add_all_wp_action_functions();
if( get_site_option( 'ias_update_available' , FALSE ) == TRUE ) {
//	array_push( $ias_sticky_messages , 'The Instant Affiliate Software Base Plugin is Out of Date. Please update to a newer version.' );
}

/**
 * Run Filter for Plugin Meta Row
 */
add_filter( 'plugin_row_meta', 'ias_filter_plugin_meta', 10, 2 );
if(checkBrands() != TRUE) {
	array_push($ias_sticky_messages, 'Some of your brands are missing critical information required for them to work.<br />Please update them <a href="admin.php?page=ias-brands">here</a>');
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