<div class="wrap">
	<h2><?php _e('Instant Affiliate Program Tracking',IAS_TEXTDOMAIN); ?></h2>
	<p><?php _e('Welcome to the 3rd Party Tracking System Integration Interface. From this interface you will be able to manage your integration with a 3rd Party Tracking System.',IAS_TEXTDOMAIN); ?></p>
	<p><?php _e('For more information on how to use this interface, visit:',IAS_TEXTDOMAIN); ?> <a href="http://rm.14all.me/projects/instant-affiliate-software-ias-plugin/wiki/Tracking_Interface#How-to-Use" class="" target="_blank">IAS Wiki - Tracking Interface - How to Use</a></p>
	<div style="float:none; clear:both; over-flow:auto;">
		<div style="float:left; width: 40%;">
			<h3>Server Postback URLs</h3>
			<table class="widefat" width="100%" cellpadding="0" cellpadding="0" role="table">
				<thead>
					<tr>
						<td colspan="2">
							<p><?php _e('Please place your system\'s ',IAS_TEXTDOMAIN); ?><a href="http://rm.14all.me/projects/instant-affiliate-software-ias-plugin/wiki/Dictionary#Server_Postback_URLs" class="" target="_blank"><?php _e('Server Postback URLs',IAS_TEXTDOMAIN); ?></a> <?php _e('In their appropriate fields.',IAS_TEXTDOMAIN); ?></p>
							<p><?php _e('Please Note: If your URL does not contain <strong>http://</strong> or <strong>https://</strong> it will not work correctly.',IAS_TEXTDOMAIN); ?></p>
						</td>
					</tr>
					<tr>
						<th><?php _e('Trigger',IAS_TEXTDOMAIN); ?></th>
						<th><?php _e('Postback URL',IAS_TEXTDOMAIN); ?></th>
					</tr>
				</thead>
				<tbody>
				<?php
					do_action('ias_tracking_form_generation');
				?>
				</tbody>
			</table>
		</div>
	</div>
</div>