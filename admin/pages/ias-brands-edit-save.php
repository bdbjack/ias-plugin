<?php
if(!isset($_POST['id']) || strlen($_POST['id']) == 0) {
	header("location: admin.php?page=ias-brands");
}
$brand = $wpdb->get_row( $wpdb->prepare( ias_fix_db_prefix("SELECT * FROM `{{ias}}brands` WHERE `id` = %d") , $_POST['id'] ) , ARRAY_A );
if($brand['isBDB'] == 1) {
	$updateArray = array(
		'campaignID' => $_POST['campaignID'],
		'licenseKey' => $_POST['licenseKey'],
	);
} else {
	$updateArray = array(
		'name' => $_POST['name'],
		'URL' => $_POST['URL'],
		'loginByCredsURL' => $_POST['loginByCredsURL'],
		'logoURL' => $_POST['logoURL'],
		'apiURL' => $_POST['apiURL'],
		'apiUser' => $_POST['apiUser'],
		'apiPass' => $_POST['apiPass'],
		'campaignID' => $_POST['campaignID'],
	);
}
$wpdb->update( ias_fix_db_prefix('{{ias}}brands') , $updateArray , array('id' => $_POST['id']) );
header("location: admin.php?page=ias-brands");
?>