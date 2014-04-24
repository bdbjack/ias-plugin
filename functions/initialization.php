<?php
	function ias_parse_request() {
		do_action('ias_init');
		do_action('ias_visit',$_GET);
	}
?>