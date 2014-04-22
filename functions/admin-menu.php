<?php
/**
 * Admin Menu Functions
 * Used to create the admin menus
 */
function ias_admin_menu() {
	$pages = array(
		array(
			'title' => 'IAS',
			'menu_title' => 'IAS',
			'capability' => 'activate_plugins',
			'menu_slug' => 'ias',
			'function' => array('ias_admin_page','ias_load_admin_page'),
			'icon' => NULL,
			'is_sub' => FALSE,
			'sub_of' => NULL,
		),
		array(
			'title' => 'IAS Brands',
			'menu_title' => 'IAS Brands',
			'capability' => 'activate_plugins',
			'menu_slug' => 'ias-brands',
			'function' => array('ias_admin_page','ias_load_admin_page'),
			'icon' => NULL,
			'is_sub' => TRUE,
			'sub_of' => 'ias',
		),
		array(
			'title' => 'IAS Brands Add',
			'menu_title' => 'IAS Brands Add',
			'capability' => 'activate_plugins',
			'menu_slug' => 'ias-brands-add',
			'function' => array('ias_admin_page','ias_load_admin_page'),
			'icon' => NULL,
			'is_sub' => TRUE,
			'sub_of' => 'ias-brands',
		),
		array(
			'title' => 'IAS Brands Add Save',
			'menu_title' => 'IAS Brands Add Save',
			'capability' => 'activate_plugins',
			'menu_slug' => 'ias-brands-add-save',
			'function' => array('ias_admin_page','ias_load_admin_page'),
			'icon' => NULL,
			'is_sub' => TRUE,
			'sub_of' => 'ias-brands-add',
		),
		array(
			'title' => 'IAS Brands Edit',
			'menu_title' => 'IAS Brands Edit',
			'capability' => 'activate_plugins',
			'menu_slug' => 'ias-brands-edit',
			'function' => array('ias_admin_page','ias_load_admin_page'),
			'icon' => NULL,
			'is_sub' => TRUE,
			'sub_of' => 'ias-brands',
		),
		array(
			'title' => 'IAS Brands Edit Save',
			'menu_title' => 'IAS Brands Edit Save',
			'capability' => 'activate_plugins',
			'menu_slug' => 'ias-brands-edit-save',
			'function' => array('ias_admin_page','ias_load_admin_page'),
			'icon' => NULL,
			'is_sub' => TRUE,
			'sub_of' => 'ias-brands-edit',
		),
		//////
		array(
			'title' => 'IAS Tracking',
			'menu_title' => 'IAS Tracking',
			'capability' => 'activate_plugins',
			'menu_slug' => 'ias-tracking',
			'function' => array('ias_admin_page','ias_load_admin_page'),
			'icon' => NULL,
			'is_sub' => TRUE,
			'sub_of' => 'ias',
		),
		array(
			'title' => 'IAS Tracking Save',
			'menu_title' => 'IAS Tracking Save',
			'capability' => 'activate_plugins',
			'menu_slug' => 'ias-tracking-save',
			'function' => array('ias_admin_page','ias_load_admin_page'),
			'icon' => NULL,
			'is_sub' => TRUE,
			'sub_of' => 'ias-tracking',
		),
	);
	foreach ($pages as $page) {
		if($page['is_sub'] == FALSE) {
			add_menu_page( $page['title'] , $page['menu_title'] , $page['capability'] , $page['menu_slug'] , $page['function'] , $page['icon'] );
		} else {
			add_submenu_page ( $page['sub_of'] , $page['title'] , $page['menu_title'] , $page['capability'] , $page['menu_slug'] , $page['function'] );
		}
	}
}
class ias_admin_page {
	static function ias_load_admin_page() {
		global $wpdb, $wp_version;
		$page = $_GET['page'];
		$page_title = ucwords(str_replace('-', ' ', $page));
		if(file_exists(IAS_BASE . '/admin/pages/' . $page . '.php')) {
			require_once(IAS_BASE . '/admin/pages/' . $page . '.php');
		} else {
			ias_add_error('File ' . $page . '.php could not be found');
		}
	}
} // end of ias_admin_page class

?>