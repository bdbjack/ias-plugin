<div class="wrap">
	<h2><?php _e('Instant Affiliate Program',IAS_TEXTDOMAIN); ?></h2>
	<p><?php _e('Welcome to the IAS Information page. For more information about IAS, visit: ',IAS_TEXTDOMAIN); ?> <a href="http://rm.14all.me/projects/instant-affiliate-software-ias-plugin" target="_blank">http://rm.14all.me/projects/instant-affiliate-software-ias-plugin</a>.</p>
	<p><?php _e('To report any bugs, visit:',IAS_TEXTDOMAIN); ?> <a href="http://rm.14all.me/projects/instant-affiliate-software-ias-plugin/issues/new" target="_blank">http://rm.14all.me/projects/instant-affiliate-software-ias-plugin/issues/new</a> <?php _e('or send an email to:',IAS_TEXTDOMAIN); ?> <a href="mailto:ias_bugs@14all.me" target="_blank">ias_bugs@14all.me</a></p>
	<div style="float:none; clear:both; display:block; width:100%;overflow-x: auto; margin-bottom: 15px;">
		<div style="float:left; width:50%;">
			<table class="widefat" width="100%" cellpadding="0" cellspacing="0" role="table">
				<thead>
					<tr>
						<th colspan="2"><?php _e('Installation Information',IAS_TEXTDOMAIN); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th><?php _e('Server Software',IAS_TEXTDOMAIN); ?></th>
						<td><?php print($_SERVER['SERVER_SOFTWARE']); ?></td>
					</tr>
					<tr>
						<th><?php _e('PHP Version',IAS_TEXTDOMAIN); ?></th>
						<td><?php print(phpversion()); ?></td>
					</tr>
					<tr>
						<th><?php _e('MySQL Version',IAS_TEXTDOMAIN); ?></th>
						<td><?php print( $wpdb->get_var( "SELECT VERSION( ) as `version`" ) ); ?></td>
					</tr>
					<tr>
						<th><?php _e('WordPress Version',IAS_TEXTDOMAIN); ?></th>
						<td><?php print( $wp_version ); ?></td>
					</tr>
					<tr>
						<th><?php _e('Server IP',IAS_TEXTDOMAIN); ?></th>
						<td><?php print( $_SERVER['SERVER_ADDR'] ); ?></td>
					</tr>
					<tr>
						<th><?php _e('Server External IP',IAS_TEXTDOMAIN); ?></th>
						<td><?php 
						$ip_feedback = wp_remote_get('http://httpbin.org/ip');
						$ip_feedback_array = json_decode($ip_feedback['body'],true);
						print( $ip_feedback_array['origin'] ); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div style="float:left; width:50%;">
			<table class="widefat" width="100%" cellpadding="0" cellspacing="0" role="table">
				<thead>
					<tr>
						<th colspan="2"><?php _e('Open Plugin Issues',IAS_TEXTDOMAIN); ?></th>
					</tr>
					<tr>
						<td><?php _e('Issue',IAS_TEXTDOMAIN); ?></td>
						<td><?php _e('Last Update',IAS_TEXTDOMAIN); ?></td>
					</tr>
				</thead>
				<tbody><?php
					$raw_xml_fetch = wp_remote_get('https://rm.14all.me/projects/ias/issues.atom?key=cdf829065376dbf75a6bb57829f2abb862c611e3');
					if(!is_wp_error( $raw_xml_fetch ) ) {
						$xml = $raw_xml_fetch['body'];
						$xml_obj = new SimpleXMLElement($xml);
						foreach ($xml_obj->entry as $issue) {
						print('<tr>' . "\r\n");
						?>
						<td><a href="<?php print($issue->id); ?>" class="" target="_blank"><?php _e($issue->title,IAS_TEXTDOMAIN); ?></a></td>
						<td><a href="<?php print($issue->id); ?>" class="" target="_blank"><?php print($issue->updated); ?></a></td>
						<?php
						print('</tr>' . "\r\n");
						}
					}
				?></tbody>
				<!-- <tfoot>
					<tr>
						<th colspan="2"><a href="http://rm.14all.me/projects/instant-affiliate-software-ias-plugin/issues/new" class="" target="_blank"><?php _e('Open a New Issue',IAS_TEXTDOMAIN); ?></a></th>
					</tr>
				</tfoot> -->
			</table>
		</div>
	</div>
	<div style="float:none; clear:both; display:block; width:100%;overflow-x: auto;">
		<div style="float:right; width:50%;">
			<table class="widefat" width="100%" cellpadding="0" cellspacing="0" role="table">
				<thead>
					<tr>
						<th colspan="2"><?php _e('Latest Dev Team Activity',IAS_TEXTDOMAIN); ?></th>
					</tr>
					<tr>
						<td><?php _e('Time',IAS_TEXTDOMAIN); ?></td>
						<td><?php _e('Activity',IAS_TEXTDOMAIN); ?></td>
					</tr>
				</thead>
				<tbody><?php
					$raw_xml_fetch = wp_remote_get('https://rm.14all.me/projects/ias/activity.atom?key=cdf829065376dbf75a6bb57829f2abb862c611e3&show_changesets=1&show_documents=1&show_files=1&show_issues=1&show_messages=1&show_wiki_edits=1&utf8=%E2%9C%93');
					if(!is_wp_error( $raw_xml_fetch ) ) {
						$xml = $raw_xml_fetch['body'];
						$xml_obj = new SimpleXMLElement($xml);
						foreach ($xml_obj->entry as $issue) {
						print('<tr>' . "\r\n");
						?>
						<td><a href="<?php print($issue->id); ?>" class="" target="_blank"><?php print($issue->updated); ?></a></td>
						<td><a href="<?php print($issue->id); ?>" class="" target="_blank"><?php _e($issue->title,IAS_TEXTDOMAIN); ?></a></td>
						<?php
						print('</tr>' . "\r\n");
						}
					}
				?></tbody>
			</table>
		</div>
	</div>
</div>