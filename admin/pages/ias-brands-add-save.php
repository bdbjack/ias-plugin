<?php
	$req_vars = array(
		'name',
		'URL',
		'loginByCredsURL',
		'logoURL',
		'campaignID',
		'apiURL',
		'apiUser',
		'apiPass',
	);
	foreach ($req_vars as $var) {
		if(!isset($_POST[$var]) || strlen($_POST[$var]) == 0) {
			header("location: admin.php?page=ias-brands-add");
		}
	}
	// Let's make sure that the brand doesn't already exist by url
	$existing_count = $wpdb->get_var( $wpdb->prepare( ias_fix_db_prefix( "SELECT COUNT(*) as `count` FROM `{{ias}}brands` WHERE `URL` LIKE %s" ), $_POST['URL'] ) );
	if($existing_count != 0) {
		header("location: admin.php?page=ias-brands-add");
	}
	// Insert the information
	$wpdb->insert( ias_fix_db_prefix('{{ias}}brands') , $_POST );
	$insertId = $wpdb->insert_id;
	// Update the region
	$region_original = json_decode( $wpdb->get_var( ias_fix_db_prefix("SELECT `brands` FROM `{{ias}}regions` WHERE `id` = 3") ) , true );
	array_push($region_original, $insertId);
	$wpdb->update( ias_fix_db_prefix('{{ias}}regions') , array('brands' => json_encode($region_original)) , array('id' => 3) );
	header("location: admin.php?page=ias-brands");
?>