<?php
	if(!isset($_POST['bug_contents']) || strlen($_POST['bug_contents']) == 0) {
		header("location: admin.php?page=ias-bugs&success=0");
	}
	$ip_feedback = wp_remote_get('http://httpbin.org/ip');
	if(!is_wp_error()) {
		$ip_feedback_array = json_decode($ip_feedback['body'],true);
	} else {
		$ip_feedback_array['origin'] = 'Error';
	}
	// Gather Information
	$info = array(
		'issue' => array(
				'key' => '59930c6460e8e71ef58b4cc95d852153bf21b510',
				'subject' => 'New bug from ' . get_bloginfo('name') . ' (' . get_bloginfo('wpurl') . ')',
				'project_id' => MUSKETEERS_PROJECT_ID,
				'tracker_id' => 1,
				'status' => array(
						'id' => 1,
						'name' => "New",
					),
				'fixed_version_id' => IAS_VERSION_ID,
				'description' => $_POST['bug_contents'],
				'custom_fields' => array(
					array(
						'name' => 'Web Server Version',
						'id' => 2,
						'value' => $_SERVER['SERVER_SOFTWARE'],
					),
					array(
						'name' => 'PHP Version',
						'id' => 3,
						'value' => phpversion(),
					),
					array(
						'name' => 'MySQL Version',
						'id' => 4,
						'value' => $wpdb->get_var( "SELECT VERSION( ) as `version`" ),
					),
					array(
						'name' => 'WordPress Version',
						'id' => 5,
						'value' => $wp_version,
					),
					array(
						'name' => 'WordPress Admin Email',
						'id' => 6,
						'value' => get_bloginfo('admin_email'),
					),
					array(
						'name' => 'Server IP',
						'id' => 7,
						'value' => $_SERVER['SERVER_ADDR'],
					),
					array(
						'name' => 'Server External IP',
						'id' => 8,
						'value' => $ip_feedback_array['origin'],
					),
				),
			),
	);

	$api = new ias_redmine( $info );
	if($api->response == 201) {
		header("location: admin.php?page=ias-bugs&success=1");
	} else {
		header("location: admin.php?page=ias-bugs&success=0");
	}
?>