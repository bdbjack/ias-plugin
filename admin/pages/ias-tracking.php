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
			<h3><?php _e('Report Actions (Pixels)',IAS_TEXTDOMAIN); ?></h3>
			<form action="admin.php?page=ias-tracking-save-pixels&noheader=true" method="POST" role="form">
				<table class="widefat" width="100%" cellpadding="0" cellpadding="0" role="table">
					<thead>
						<tr>
							<th colspan="2"><?php _e('Add a new reporting action (pixel)',IAS_TEXTDOMAIN); ?></th>
						</tr>
						<tr>
							<th><?php _e('Trigger',IAS_TEXTDOMAIN); ?></th>
							<th><?php _e('Type',IAS_TEXTDOMAIN); ?></th>
						</tr>
						<tr>
							<td width="50%;">
								<select name="trigger" style="width:100%;">
									<option value="visit"><?php _e('Visit',IAS_TEXTDOMAIN); ?></option>
									<option value="emailCap"><?php _e('Email Capture',IAS_TEXTDOMAIN); ?></option>
									<option value="leadGen"><?php _e('Lead Generation',IAS_TEXTDOMAIN); ?></option>
									<option value="customerGen"><?php _e('Customer Generation',IAS_TEXTDOMAIN); ?></option>
									<option value="deposit"><?php _e('Deposit',IAS_TEXTDOMAIN); ?></option>
								</select>
							</td>
							<td width="50%;">
								<select name="type" style="width:100%;" id="pixel-type">
									<option value="server" data-box-type="text"><?php _e('Server Postback',IAS_TEXTDOMAIN); ?></option>
									<option value="image" data-box-type="html"><?php _e('Image Pixel',IAS_TEXTDOMAIN); ?></option>
									<option value="iframe" data-box-type="html"><?php _e('Iframe Pixel',IAS_TEXTDOMAIN); ?></option>
									<option value="js" data-box-type="html"><?php _e('Javascript',IAS_TEXTDOMAIN); ?></option>
								</select>
							</td>
						</tr>
						<tr>
							<th colspan="2"><?php _e('Content',IAS_TEXTDOMAIN); ?></th>
						</tr>
						<tr>
							<td colspan="2">
								<div style="width:100%; min-height: 150px;" id="new_pixel_box"></div>
								<textarea id="new_pixel_textarea" name="content"></textarea>
							</td>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>
								<input type="submit" class="button button-primary" value="<?php _e('Add',IAS_TEXTDOMAIN); ?>" />
								<a href="admin.php?page=ias-tracking" class="button"><?php _e('Cancel',IAS_TEXTDOMAIN); ?></a>
							</th>
						</tr>
					</tfoot>
				</table>
				<table class="widefat" width="100%" cellpadding="0" cellpadding="0" role="table">
					<tr>
						<th>&nbsp;</th>
						<th><?php _e('Type',IAS_TEXTDOMAIN); ?></th>
						<th><?php _e('Trigger',IAS_TEXTDOMAIN); ?></th>
						<th><?php _e('Content',IAS_TEXTDOMAIN); ?></th>
					</tr>
					<?php
						$pixels = $wpdb->get_results( ias_fix_db_prefix( "SELECT * FROM `{{ias}}postbacks`" ), ARRAY_A);
						foreach ($pixels as $pixel) {
							?>
							<tr>
								<td width="20px;"><input type="checkbox" name="remove[]" value="<?php print($pixel['id']); ?>" /></td>
								<td width="15%">
									<?php
										switch ($pixel['type']) {
											case 'server':
												 _e('Server Postback',IAS_TEXTDOMAIN);
												break;

											case 'image':
												 _e('Image Pixel',IAS_TEXTDOMAIN);
												break;

											case 'iframe':
												 _e('Iframe Pixel',IAS_TEXTDOMAIN);
												break;

											case 'js':
												 _e('Javascript',IAS_TEXTDOMAIN);
												break;
										}
									?>
								</td>
								<td width="25%">
									<?php
										switch ($pixel['trigger']) {
											case 'visit':
												 _e('Visit',IAS_TEXTDOMAIN);
												break;

											case 'emailCap':
												 _e('Email Capture',IAS_TEXTDOMAIN);
												break;

											case 'leadGen':
												 _e('Lead Generation',IAS_TEXTDOMAIN);
												break;

											case 'customerGen':
												 _e('Customer Generation',IAS_TEXTDOMAIN);
												break;

											case 'deposit':
												 _e('Deposit',IAS_TEXTDOMAIN);
												break;
										}
									?>
								</td>
								<td>
									<div class="acebox" id="ace_box_pixel_<?php print($pixel['id']); ?>" data-box-type="<?php
										switch ($pixel['type']) {
											case 'server':
												print('text');
												break;
											
											default:
												print('html');
												break;
										}
									?>" style="width:100%; min-height: 150px;"><?php
										print(htmlentities($pixel['content']));
										?></div>
								</td>
							</tr>
							<?php
						}
					?>
					<tr>
						<th colspan="4">
							<input type="submit" class="button button-primary" value="<?php _e('Remove Selected',IAS_TEXTDOMAIN); ?>" />
							<a href="admin.php?page=ias-tracking" class="button"><?php _e('Cancel',IAS_TEXTDOMAIN); ?></a>
						</th>
					</tr>
				</table>
				<script type="text/javascript">
					var pixelBox;
					var textarea;
					jQuery(function() {
						pixelBox = ace.edit("new_pixel_box");
						pixelBox.setTheme("ace/theme/chrome");
						pixelBox.getSession().setMode("ace/mode/text");
						textarea = jQuery("#new_pixel_textarea");
						textarea.hide();
						pixelBox.getSession().setValue(textarea.val());
						pixelBox.getSession().on('change', function(){
						  textarea.val(pixelBox.getSession().getValue());
						});
						jQuery("#pixel-type").on('change',function() {
							var type = jQuery("#pixel-type option:selected").attr('data-box-type');
							pixelBox.getSession().setMode("ace/mode/" + type);
						});
						jQuery(".acebox").each(function() {
							var id = jQuery(this).attr('id');
							var pixelWindow = ace.edit(id);
							pixelWindow.setTheme("ace/theme/monokai");
							pixelWindow.getSession().setMode("ace/mode/" + jQuery(this).attr('data-box-type'));
							pixelWindow.setReadOnly(true);
						});
					});
				</script>
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
					<tr>
					    <th>{ip}</th>
					    <td><?php _e('The IP Address from which the user triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{iso}</th>
					    <td><?php _e('The ISO of the country from which the user triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{spotid}</th>
					    <td><?php _e('The SpotOption ID of the country from which the user triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{ias_region}</th>
					    <td><?php _e('The IAS Region ID of the country from which the user triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{country_prefix}</th>
					    <td><?php _e('The Dialing Prefix of the country from which the user triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{location_country}</th>
					    <td><?php _e('The name of the country from which the user triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{location_region_code}</th>
					    <td><?php _e('The 2 digit abbreviation of the region from which the user triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{location_region_name}</th>
					    <td><?php _e('The name of the region from which the user triggered the action',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{location_city_name}</th>
					    <td><?php _e('The name of the city from which the user triggered the action',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{location_time_zone}</th>
					    <td><?php _e('The time zone from which the user triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{location_continent_code}</th>
					    <td><?php _e('The 2 digit abbreviation of the continent from which the user triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{location_postal_code}</th>
					    <td><?php _e('The postal code from which the user triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{customer_id}</th>
					    <td><?php _e('The id of the customer which triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{customer_FirstName}</th>
					    <td><?php _e('The first name of the customer which triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{customer_LastName}</th>
					    <td><?php _e('The last name of the customer which triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{customer_email}</th>
					    <td><?php _e('The email of the customer which triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{customer_cellphone}</th>
					    <td><?php _e('The mobile phone number of the customer which triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{customer_phone}</th>
					    <td><?php _e('The phone number of the customer which triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{customer_fax}</th>
					    <td><?php _e('The fax number of the customer which triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{customer_firstDepositDate}</th>
					    <td><?php _e('The first deposit date of the customer which triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{customer_Country}</th>
					    <td><?php _e('The country of residence of the customer which triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{customer_registrationCountry}</th>
					    <td><?php _e('The country of registration of the customer which triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{customer_City}</th>
					    <td><?php _e('The city of residence of the customer which triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{customer_state}</th>
					    <td><?php _e('The 2 letter abbreviation of the state of residence of the customer which triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{customer_street}</th>
					    <td><?php _e('The street name of the customer which triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{customer_houseNumber}</th>
					    <td><?php _e('The house number of the customer which triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{customer_aptNumber}</th>
					    <td><?php _e('The apartment number of the customer which triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{customer_regTime}</th>
					    <td><?php _e('The registration time of the customer which triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{customer_accountBalance}</th>
					    <td><?php _e('The current account balance of the customer which triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{customer_currency}</th>
					    <td><?php _e('The currency of the customer which triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{a_aid}</th>
					    <td><?php _e('The value of the parameter a_aid of the user who triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{a_bid}</th>
					    <td><?php _e('The value of the parameter a_bid of the user who triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{a_cid}</th>
					    <td><?php _e('The value of the parameter a_cid of the user who triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
					<tr>
					    <th>{tracker}</th>
					    <td><?php _e('The value of the parameter tracker of the user who triggered the action.',IAS_TEXTDOMAIN); ?></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>