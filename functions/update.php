<?php
/**
 * functions related to auto-updating of the WordPress Plugin
 */
function ias_updates() {
	ias_update_check();
	do_action( 'ias_update' );
}

function ias_update_check () {
	$rm_client = new Artax\Client;
 	$rm_artax_request = (new Artax\Request)->setUri('https://rm.14all.me/versions/' . IAS_VERSION_ID . '.json');
	 	$rm_artax_request->setProtocol('1.0');
	 	$rm_return = NULL;
	 	$rm_request = NULL;
	 	$rm_real_return = NULL;
	try {
		$rm_request = $rm_client->request($rm_artax_request);
		$rm_return = $rm_request->getBody();
 	}
 	catch (Artax\ClientException $e) {
 		$rm_return = $e;
 	}
 	try {
 		$rm_real_return = json_decode($rm_return,true);
 	}
 	catch (exception $e) {
 		$rm_real_return = $e;
 	}
 	$current_head = $rm_real_return['version']['description'];
 	/**
 	 * Check with the repo checker
 	 */
 	$remote_info_raw = wp_remote_get( 'http://repo.14all.me/' . MUSKETEERS_PROJECT_ID . '/' . MUSKETEERS_REPO_NAME . '/' );
 	if(!is_wp_error( $remote_info_raw ) ) {
 		$remote_info = json_decode( $remote_info_raw['body'], true );
 	} else {
 		$remote_info = array(
 			'version' => 'current',
 			'name' => IAS_VERSION,
 			'commit' => $current_head,
 		);
 	}
 	if( $current_head !== $remote_info['commit'] ) {
 		update_site_option( 'ias_update_available' , TRUE );
 	} else {
 		update_site_option( 'ias_update_available' , FALSE );
 	}
 	update_site_option( 'ias_last_update' , time() );
}
?>