<?php
	if(!isset($_POST['bug_contents']) || strlen($_POST['bug_contents']) == 0) {
		header("location: admin.php?page=ias-bugs&success=0");
	}
	$ip_feedback = wp_remote_get('http://httpbin.org/ip');
	$ip_feedback_array = json_decode($ip_feedback['body'],true);
	// Gather Information
	$info = array(
		'issue' => array(
				'subject' => 'New bug from ' . get_bloginfo('name') . ' (' . get_bloginfo('wpurl') . ')',
				'project_id' => 10,
				'tracker_id' => 1,
				'description' => $_POST['bug_contents'],
				'custom_fields' => array(
					array(
						'name' => 'Web Server Version',
						'id' => 1,
						'value' => $_SERVER['SERVER_SOFTWARE'],
					),
					array(
						'name' => 'PHP Version',
						'id' => 2,
						'value' => phpversion(),
					),
					array(
						'name' => 'MySQL Version',
						'id' => 3,
						'value' => $wpdb->get_var( "SELECT VERSION( ) as `version`" ),
					),
					array(
						'name' => 'WordPress Version',
						'id' => 4,
						'value' => $wp_version,
					),
					array(
						'name' => 'WordPress Admin Email',
						'id' => 5,
						'value' => get_bloginfo('admin_email'),
					),
					array(
						'name' => 'Server IP',
						'id' => 6,
						'value' => $_SERVER['SERVER_ADDR'],
					),
					array(
						'name' => 'Server External IP',
						'id' => 7,
						'value' => $ip_feedback_array['origin'],
					),
				),
			),
	);

	$api_call = wp_remote_post(
		'https://rm.14all.me/issues.json',
		array(
			'method' => 'POST',
			'timeout' => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(
				'Content-Type' => 'application/json',
				//'Authorization' => 'Basic ' . base64_encode( 'iasuser' . ':' . '1q2w3e$r' ),
				'X-Redmine-API-Key' => 'e5b172cfe0d702e1f126d2d87038620c4c1921c3'
				),
			'cookies' => array(),
			'body' => json_encode($info),
		)
	);
	header("location: admin.php?page=ias-bugs&success=2");
?>