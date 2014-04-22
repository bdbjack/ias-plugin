<?php
foreach ($_POST['selected'] as $brand) {
	$region_original = json_decode( $wpdb->get_var( ias_fix_db_prefix("SELECT `brands` FROM `{{ias}}regions` WHERE `id` = 3") ) , true );
	$region_updated = array();
	foreach ($region_original as $b) {
		if($b != $brand) {
			array_push($region_updated,$b);
		}
	}
	$wpdb->update( ias_fix_db_prefix('{{ias}}regions') , array('brands' => json_encode($region_updated)) , array('id' => 3) );
	$wpdb->delete( ias_fix_db_prefix('{{ias}}brands') , array('id' => $brand));
}
header("location: admin.php?page=ias-brands");
?>