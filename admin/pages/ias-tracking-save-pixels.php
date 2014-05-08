<?php
if( strlen( $_POST['trigger'] ) > 0 && strlen( $_POST['type'] ) > 0 && strlen( $_POST['content'] ) > 0 ) {
	$insert_array = array(
		'content' => stripslashes( $_POST['content'] ),
		'trigger' => $_POST['trigger'],
		'type' => $_POST['type'],
	);
	$wpdb->insert( ias_fix_db_prefix( '{{ias}}postbacks' ) , $insert_array );
}
else {
	foreach ($_POST['remove'] as $pixel) {
		$wpdb->delete( ias_fix_db_prefix( '{{ias}}postbacks') , array('id' => $pixel ) );
	}
}
header("location: admin.php?page=ias-tracking");
?>