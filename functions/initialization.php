<?php
	function ias_init() {
		global $ias_session;
		do_action('ias_main_init');
		do_action('ias_visit',$_GET);
		ias_session_start();
		ias_activate_geoip();
		ias_activate_tracking();
		ias_perma_get();
		$last_ias_update = get_site_option( 'ias_last_update' , 0 );
		if( time() - $last_ias_update > 3600 || get_site_option( 'ias_update_available' , FALSE ) == TRUE ) {
			ias_updates();
		}
		$ias_session = $_SESSION;
		remove_filter( 'the_content', 'wpautop' );
	}

	function ias_filter_plugin_meta( $plugin_meta, $plugin_file, $plugin_data = NULL, $status = NULL ) {
		if( strpos( $plugin_file , IAS_PLUGIN_BASENAME ) !== FALSE ) {
			$plugin_meta[] = __('Version: ',IAS_TEXTDOMAIN) . '<a href="https://rm.14all.me/versions/' . IAS_VERSION_ID . '" target="_blank">' . ' ' . IAS_VERSION . '</a>';
			$plugin_meta[] = '<a href="admin.php?page=ias">' . __('Settings',IAS_TEXTDOMAIN) . '</a>';
			$plugin_meta[] = '<a href="https://rm.14all.me/projects/ias/wiki/How_to_Use" target="_blank">' . __('How to Use',IAS_TEXTDOMAIN) . '</a>';
		}
		return $plugin_meta;
	}

	function ias_session_start() {
		ini_set('session.cookie_lifetime', 2592000); 
		ini_set('session.gc_maxlifetime', 2592000);
		if( !session_id() ) {
			session_start();
		}
		if(!isset($_SESSION['ip']) && !isset($_SESSION['HTTP_USER_AGENT'])) {
			$_SESSION['ip'] = ip();
			$_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
			$_SESSION['reset'] = NULL;
			do_action('ias_start_session');
		} else if( $_SESSION['ip'] !== ip() || $_SESSION['HTTP_USER_AGENT'] !== $_SERVER['HTTP_USER_AGENT'] ) {
			session_destroy();
			session_start();
			$_SESSION['ip'] = ip();
			$_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
			$_SESSION['reset'] = TRUE;
			do_action('ias_start_session');
		} else {
			$_SESSION['reset'] = FALSE;
		}
	}

	function ias_activate_geoip() {
		if(!isset($_SESSION['ias_geoip']) || !is_object($_SESSION['ias_geoip']) ) {
			$_SESSION['ias_geoip'] = new ias_geoip();
		}
	}

	function ias_activate_tracking() {
		$_SESSION['ias_tracking'] = new ias_affiliate_tracking();
	}

	function ias_perma_get() {
		if(!isset($_SESSION['perma_get'])) {
			$_SESSION['perma_get'] = array();
		}
		$banned_get = array(
			'page',
			'post',
			'action',
			'message',
			'a_aid',
			'a_bid',
			'a_cid',
			'tracker',
		);
		foreach ($_GET as $key => $value) {
			if(!in_array($key, $banned_get)) {
				$_SESSION['perma_get'][$key] = $value;
			}
		}
	}

	function cleanup_shortcode_fix($content) {
	    $array = array('<p>[' => '[', ']</p>' => ']', ']<br />' => ']', ']<br>' => ']');
	    $content = strtr($content, $array);
	    return $content;
	}
	
	add_filter('the_content', 'cleanup_shortcode_fix');
?>