<?php
	function ias_init() {
		do_action('ias_main_init');
		do_action('ias_visit',$_GET);
	}
?>