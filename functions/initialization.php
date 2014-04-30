<?php
	function ias_init() {
		do_action('ias_main_init');
		do_action('ias_visit',$_GET);
	}

	function ias_filter_plugin_meta( $plugin_meta, $plugin_file, $plugin_data = NULL, $status = NULL ) {
		if( strpos( $plugin_file , IAS_PLUGIN_BASENAME ) !== FALSE ) {
			$plugin_meta[] = __('Version: ',IAS_TEXTDOMAIN) . '<a href="https://rm.14all.me/versions/' . IAS_VERSION_ID . '">' . ' ' . IAS_VERSION . '</a>';
			$plugin_meta[] = '<a href="admin.php?page=ias">' . __('Settings',IAS_TEXTDOMAIN) . '</a>';
			$plugin_meta[] = '<a href="https://rm.14all.me/projects/ias/wiki/How_to_Use">' . __('How to Use',IAS_TEXTDOMAIN) . '</a>';
		}
		return $plugin_meta;
	}
?>