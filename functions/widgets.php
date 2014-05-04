<?php
	function ias_widgets_init() {
		$widgets = array(
			'ias_login_form_widget',
		);

		foreach ( $widgets as $widget ) {
			 register_widget( $widget );
		}
	}
?>