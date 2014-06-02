<?php
/**
 * Set up automatic handling of all wordpress functions
 * All action functions add a do_action( ias_{action} ) function to the end of the function to allow sub-modules to hook into the function.
 */
function ias_add_all_wp_action_functions() {
 $actions = array(
 	'after_setup_theme',
	'auth_cookie_malformed',
	'auth_cookie_valid',
	'set_current_user',
	'init',
	'widgets_init',
	'register_sidebar',
	'wp_register_sidebar_widget',
	'wp_default_scripts',
	'wp_default_styles',
	'admin_bar_init',
	'add_admin_bar_menus',
	'wp_loaded',
	'parse_request',
	'send_headers',
	'parse_query',
	'pre_get_posts',
	'posts_selection',
	'wp',
	'template_redirect',
	'get_header',
	'wp_enqueue_scripts',
	'wp_head',
	'wp_print_styles',
	'wp_print_scripts',
	'get_search_form',
	'loop_start',
	'the_post',
	'get_template_part_content',
	'loop_end',
	'get_sidebar',
	'dynamic_sidebar',
	'get_search_form',
	'pre_get_comments',
	'wp_meta',
	'get_footer',
	'get_sidebar',
	'twentyeleven_credits',
	'wp_footer',
	'wp_print_footer_scripts',
	'admin_bar_menu',
	'wp_before_admin_bar_render',
	'wp_after_admin_bar_render',
	'shutdown',
 );

 $admin_actions = array(
 	'muplugins_loaded',
	'registered_taxonomy',
	'registered_post_type',
	'plugins_loaded',
	'sanitize_comment_cookies',
	'setup_theme',
	'load_textdomain',
	'after_setup_theme',
	'load_textdomain',
	'auth_cookie_valid',
	'set_current_user',
	'init',
	'widgets_init',
	'register_sidebar',
	'wp_register_sidebar_widget',
	'wp_default_scripts',
	'wp_default_styles',
	'admin_bar_init',
	'add_admin_bar_menus',
	'wp_loaded',
	'auth_cookie_valid',
	'auth_redirect',
	'_admin_menu',
	'admin_menu',
	'admin_init',
	'current_screen',
	'send_headers',
	'pre_get_posts',
	'posts_selection',
	'wp',
	'admin_xml_ns',
	'admin_xml_ns',
	'admin_enqueue_scripts',
	'admin_print_styles',
	'admin_print_scripts',
	'wp_print_scripts',
	'admin_head',
	'adminmenu',
	'in_admin_header',
	'admin_notices',
	'all_admin_notices',
	'restrict_manage_posts',
	'the_post',
	'pre_user_query',
	'in_admin_footer',
	'admin_footer',
	'admin_bar_menu',
	'wp_before_admin_bar_render',
	'wp_after_admin_bar_render',
	'admin_print_footer_scripts',
	'shutdown',
	'wp_dashboard_setup',
 );

 foreach ($actions as $action) {
 	if(function_exists('ias_' . $action)) {
		add_action($action,'ias_' . $action, 9);
 	}
 }

 foreach ($admin_actions as $action) {
 	if(function_exists('ias_' . $action)) {
		add_action($action,'ias_' . $action, 9);
 	}
 }

 $hooks = array(
 	
 );

 foreach ($hooks as $hook => $action) {
 	add_action($hook,$action);
 }
}

function ias_db_prefix() {
	global $wpdb;
	return $wpdb->prefix . 'ias_';
}

function ias_fix_db_prefix( $sql ) {
	$sql = str_replace('{{ias}}', ias_db_prefix(), $sql);
	return $sql;
}

function ias_activation() {
	if(PHP_VERSION_ID < 50400) {
		throw new Exception("PHP Version too low. Please upgrade your version of PHP to at least 5.4", 1);
	}
	if(get_site_option('IAS_DB_VERSION') !== IAS_DB_VERSION) {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$dir = new RecursiveDirectoryIterator(IAS_BASE . '/install/sql');
		$iterator = new RecursiveIteratorIterator($dir);
		$sqlsobj = new RegexIterator($iterator, '/^.+\.sql$/i', RecursiveRegexIterator::GET_MATCH);
		$files = array();
		$files['create'] = array();
		$files['populate'] = array();
		foreach ($sqlsobj as $name => $obj) {
			if(strpos($name, 'Create') !== FALSE) {
				array_push($files['create'],$name);
			}
			if(strpos($name, 'Populate') !== FALSE) {
				array_push($files['populate'],$name);
			}
		}
		foreach ($files['create'] as $file) {
			dbDelta( ias_fix_db_prefix ( file_get_contents( $file ) ) );
		}
		foreach ($files['populate'] as $file) {
			$sql = file_get_contents($file);
			if( checkIfTableHasRecords($sql) == FALSE ) {
				$wpdb->query( ias_fix_db_prefix ( $sql ) );
			}
		}
		update_option( "IAS_DB_VERSION", IAS_DB_VERSION );
		do_action('ias_activation');
	}
}

function checkIfTableHasRecords( $sql ) {
	global $wpdb;
	$beg = strpos($sql, '{{ias}}');
	$end = strpos($sql, '`',$beg);
	$len = $end - $beg;
	$name = substr($sql, $beg, $len);
	$name = ias_fix_db_prefix( $name );
	$query = "SELECT COUNT(*) FROM `". $name ."`";
	$count = $wpdb->get_var($query);
	if($count > 0) {
		return true;
	} else {
		return false;
	}
}

function checkBrands() {
	global $wpdb;
	$count = $wpdb->get_var( ias_fix_db_prefix( "SELECT COUNT(*) as `count` FROM `{{ias}}brands` WHERE ( `isBDB` = 1 AND `licenseKey` = '' AND `campaignID` = '') OR ( `active` = 1 AND `isBDB` = 0 AND `apiURL` = '' AND `apiUser` = '' AND `apiPass` = '' AND `campaignID` = '')" ));
	if($count > 0) {
		do_action('ias_check_brands');
		return FALSE;
	} else {
		do_action('ias_check_brands');
		return TRUE;
	}
}

function report_ias_bug( $subject , $bug ) {
	global $wpdb, $wp_version;
	$ip_feedback = wp_remote_get('http://httpbin.org/ip');
	if(!is_wp_error( $ip_feedback )) {
		$ip_feedback_array = json_decode($ip_feedback['body'],true);
	} else {
		$ip_feedback_array['origin'] = 'Error';
	}
	// Gather Information
	$info = array(
		'issue' => array(
				'key' => '59930c6460e8e71ef58b4cc95d852153bf21b510',
				'subject' => $subject,
				'project_id' => MUSKETEERS_PROJECT_ID,
				'tracker_id' => 1,
				'status' => array(
						'id' => 1,
						'name' => "New",
					),
				'fixed_version_id' => IAS_VERSION_ID,
				'description' => $bug,
				'custom_fields' => array(
					array(
						'name' => 'Web Server Version',
						'id' => 2,
						'value' => $_SERVER['SERVER_SOFTWARE'],
					),
					array(
						'name' => 'PHP Version',
						'id' => 3,
						'value' => phpversion(),
					),
					array(
						'name' => 'MySQL Version',
						'id' => 4,
						'value' => $wpdb->get_var( "SELECT VERSION( ) as `version`" ),
					),
					array(
						'name' => 'WordPress Version',
						'id' => 5,
						'value' => $wp_version,
					),
					array(
						'name' => 'WordPress Admin Email',
						'id' => 6,
						'value' => get_bloginfo('admin_email'),
					),
					array(
						'name' => 'Server IP',
						'id' => 7,
						'value' => $_SERVER['SERVER_ADDR'],
					),
					array(
						'name' => 'Server External IP',
						'id' => 8,
						'value' => $ip_feedback_array['origin'],
					),
				),
			),
	);
	$api = new ias_redmine( $info );
	if($api->response == 201) {
		return TRUE;
	} else {
		return FALSE;
	}
}
?>