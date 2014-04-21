<?php
/*
Plugin Name: Instant Affiliate Software
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
if (!defined('IAS_BASE')) {
    define('IAS_BASE', dirname( __FILE__ ) );
}

if (!defined('IAS_URL_BASE')) {
    define('IAS_URL_BASE', plugin_dir_url( __FILE__ ) );
}

if (!defined('IAS_TEXTDOMAIN')) {
    define('IAS_TEXTDOMAIN', 'tr');
}

if (!defined('IAS_SHOW_ERRORS')) {
    define('IAS_SHOW_ERRORS', TRUE);
}

if (!defined('IAS_DB_PREFIX')) {
    define('IAS_DB_PREFIX', $wpdb->prefix . 'ias_');
}

/**
 * In order to faciliate faster development, we will bypass the need to check the entire site against a license, and will simply rely on the API licensing server for individual brands
 * This will need to change in the Version 2.0 release
 */
$ias_license = true;

/**
 * Set up array for displaying error messages
 */
$ias_error_messages = array();
$ias_warning_messages = array();
$ias_sticky_messages = array();
$ias_client_messages = array();

/**
 * Set up Password Hashing
 */
require_once( ABSPATH . 'wp-includes/class-phpass.php');
$wp_hasher = new PasswordHash(16, FALSE);
?>