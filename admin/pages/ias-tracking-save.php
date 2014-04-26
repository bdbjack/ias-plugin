<?php
foreach ($_POST as $key => $value) {
	update_site_option($key,$value);
}
do_action('ias_tracking_save');
header("location: admin.php?page=ias-tracking");
?>