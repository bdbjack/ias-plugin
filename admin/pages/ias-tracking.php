<div class="wrap">
	<h2><?php _e('Instant Affiliate Program Tracking',IAS_TEXTDOMAIN); ?></h2>
	<p><?php _e('Welcome to the 3rd Party Tracking System Integration Interface. From this interface you will be able to manage your integration with a 3rd Party Tracking System.',IAS_TEXTDOMAIN); ?></p>
	<p><?php _e('For more information on how to use this interface, visit:',IAS_TEXTDOMAIN); ?> <a href="http://rm.14all.me/projects/ias/wiki/Tracking_Interface#How-to-Use" class="" target="_blank">IAS Wiki - Tracking Interface - How to Use</a></p>
	<div style="float:none; clear:both; over-flow:auto;">
		<div style="float:left; width: 40%;">
			<h3><?php _e('Tracking Policies',IAS_TEXTDOMAIN); ?></h3>
			<form action="admin.php?page=ias-tracking-save&noheader=true" method="POST" role="form">
			<table class="widefat" width="100%" cellpadding="0" cellpadding="0" role="table">
				<thead>
					<tr>
						<th><?php _e('Policy',IAS_TEXTDOMAIN); ?></th>
						<th><?php _e('Value',IAS_TEXTDOMAIN); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th><?php _e('Tracking Override',IAS_TEXTDOMAIN); ?></th>
						<td>
							<select style="width:100%;" name="tracking_ias_override_type">
								<option value="lcw" <?php if(get_site_option('tracking_ias_override_type') == 'lcw') { print('selected'); } ?>><?php _e('Last Affiliate Wins',IAS_TEXTDOMAIN); ?></option>
								<option value="fcw" <?php if(get_site_option('tracking_ias_override_type') == 'fcw') { print('selected'); } ?>><?php _e('First Affiliate Wins',IAS_TEXTDOMAIN); ?></option>
							</select>
						</td>
					</tr>
					<tr>
						<th><?php _e('Tracking Expiry',IAS_TEXTDOMAIN); ?></th>
						<td>
							<select style="width:100%;" name="tracking_ias_time">
								<option value="<?php print(( 60 * 60 * 24 * 365 * 5 ) + 1); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 365 * 5 ) + 1) { print('selected'); } ?>><?php _e('Never',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 )) { print('selected'); } ?>>1 <?php _e('Day',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 2 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 2 )) { print('selected'); } ?>>2 <?php _e('Days',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 3 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 3 )) { print('selected'); } ?>>3 <?php _e('Days',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 4 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 4 )) { print('selected'); } ?>>4 <?php _e('Days',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 5 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 5 )) { print('selected'); } ?>>5 <?php _e('Days',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 6 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 6 )) { print('selected'); } ?>>6 <?php _e('Days',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 7 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 7 )) { print('selected'); } ?>>1 <?php _e('Week',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 14 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 14 )) { print('selected'); } ?>>2 <?php _e('Weeks',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 21 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 21 )) { print('selected'); } ?>>3 <?php _e('Weeks',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 31 * 1 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 31 * 1 )) { print('selected'); } ?>>1 <?php _e('Month',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 31 * 2 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 31 * 2 )) { print('selected'); } ?>>2 <?php _e('Months',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 31 * 3 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 31 * 3 )) { print('selected'); } ?>>3 <?php _e('Months',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 31 * 4 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 31 * 4 )) { print('selected'); } ?>>4 <?php _e('Months',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 31 * 5 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 31 * 5 )) { print('selected'); } ?>>5 <?php _e('Months',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 31 * 6 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 31 * 6 )) { print('selected'); } ?>>6 <?php _e('Months',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 31 * 7 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 31 * 7 )) { print('selected'); } ?>>7 <?php _e('Months',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 31 * 8 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 31 * 8 )) { print('selected'); } ?>>8 <?php _e('Months',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 31 * 9 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 31 * 9 )) { print('selected'); } ?>>9 <?php _e('Months',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 31 * 10 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 31 * 10 )) { print('selected'); } ?>>10 <?php _e('Months',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 31 * 11 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 31 * 11 )) { print('selected'); } ?>>11 <?php _e('Months',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 365 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 365 )) { print('selected'); } ?>>1 <?php _e('Year',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 365 * 2 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 365 * 2 )) { print('selected'); } ?>>2 <?php _e('Years',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 365 * 3 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 365 * 3 )) { print('selected'); } ?>>3 <?php _e('Years',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 365 * 4 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 365 * 4 )) { print('selected'); } ?>>4 <?php _e('Years',IAS_TEXTDOMAIN); ?></option>
								<option value="<?php print( 60 * 60 * 24 * 365 * 5 ); ?>" <?php if(get_site_option('tracking_ias_time') == ( 60 * 60 * 24 * 365 * 5 )) { print('selected'); } ?>>5 <?php _e('Years',IAS_TEXTDOMAIN); ?></option>
							</select>
						</td>
					</tr>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="2">
							<input type="submit" class="button button-primary" value="<?php _e('Save',IAS_TEXTDOMAIN); ?>" />
							<a href="admin.php?page=ias-tracking" class="button"><?php _e('Cancel',IAS_TEXTDOMAIN); ?></a>
						</th>
					</tr>
				</tfoot>
			</table>
			</form>
			<h3><?php _e('Server Postback URLs',IAS_TEXTDOMAIN); ?></h3>
			<form action="admin.php?page=ias-tracking-save&noheader=true" method="POST" role="form">
			<table class="widefat" width="100%" cellpadding="0" cellpadding="0" role="table">
				<thead>
					<tr>
						<td colspan="2">
							<p><?php _e('Please place your system\'s ',IAS_TEXTDOMAIN); ?><a href="http://rm.14all.me/projects/ias/wiki/Dictionary#Server_Postback_URLs" class="" target="_blank"><?php _e('Server Postback URLs',IAS_TEXTDOMAIN); ?></a> <?php _e('In their appropriate fields.',IAS_TEXTDOMAIN); ?></p>
							<p><?php _e('Please Note: If your URL does not contain <strong>http://</strong> or <strong>https://</strong> it will not work correctly.',IAS_TEXTDOMAIN); ?></p>
						</td>
					</tr>
					<tr>
						<th><?php _e('Trigger',IAS_TEXTDOMAIN); ?></th>
						<th><?php _e('Postback URL',IAS_TEXTDOMAIN); ?></th>
					</tr>
				</thead>
				<tbody>
				<tr>
					<th><?php _e('Visit',IAS_TEXTDOMAIN); ?></th>
					<td><input type="url" style="width:100%;" name="tracking_ias_visit" value="<?php print(get_site_option('tracking_ias_visit')); ?>" /></td>
				</tr>
				<tr>
					<th><?php _e('Email Capture',IAS_TEXTDOMAIN); ?></th>
					<td><input type="url" style="width:100%;" name="tracking_ias_capture" value="<?php print(get_site_option('tracking_ias_capture')); ?>" /></td>
				</tr>
				<tr>
					<th><?php _e('Customer Registration',IAS_TEXTDOMAIN); ?></th>
					<td><input type="url" style="width:100%;" name="tracking_ias_registration" value="<?php print(get_site_option('tracking_ias_registration')); ?>" /></td>
				</tr>
				<tr>
					<th><?php _e('Customer Deposit',IAS_TEXTDOMAIN); ?></th>
					<td><input type="url" style="width:100%;" name="tracking_ias_deposit" value="<?php print(get_site_option('tracking_ias_deposit')); ?>" /></td>
				</tr>
				<?php
					do_action('ias_tracking_form_generation');
				?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="2">
							<input type="submit" class="button button-primary" value="<?php _e('Save',IAS_TEXTDOMAIN); ?>" />
							<a href="admin.php?page=ias-tracking" class="button"><?php _e('Cancel',IAS_TEXTDOMAIN); ?></a>
						</th>
					</tr>
				</tfoot>
			</table>
			</form>
		</div>
		<div style="float:left; width: 60%;">
			<h3><?php _e('Variable Information Macros',IAS_TEXTDOMAIN); ?></h3>
			<table class="widefat" width="100%" cellpadding="0" cellpadding="0" role="table">
				<thead>
					<tr>
						<td colspan="2">
							<p><?php _e('The following is a list of Variable Information Macros. These are used to input dynamically changing information into your Postback URLs.',IAS_TEXTDOMAIN); ?></p>
							<p><?php _e('For more information on Variable Information Macros, please visit ',IAS_TEXTDOMAIN); ?><a href="http://rm.14all.me/projects/ias/wiki/Dictionary#Variable_Information_Macros" class="" target="_blank"><?php _e('IAS Wiki - Dictionary - Variable Information Macros',IAS_TEXTDOMAIN); ?></a></p>
						</td>
					</tr>
				</thead>
				<tbody>
					<?php do_action('show_variable_macros'); ?>
				</tbody>
			</table>
		</div>
	</div>
</div>