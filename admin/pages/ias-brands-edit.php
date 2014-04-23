<?php
	$brand = $wpdb->get_row( $wpdb->prepare( ias_fix_db_prefix("SELECT * FROM `{{ias}}brands` WHERE `id` = %d") , $_GET['id'] ) , ARRAY_A );
	if( $brand == FALSE ) {
?>
<div class="wrap">
	<h2><?php _e('Instant Affiliate Program Brand Management - Edit a Brand',IAS_TEXTDOMAIN); ?></h2>
	<p><?php _e('Error: Could not find the brand',IAS_TEXTDOMAIN); ?></p>
	<a href="admin.php?page=ias-brands" class="button" style="margin-left: 10px;"><?php _e('Back',IAS_TEXTDOMAIN); ?></a>
</div>
<?php
	} else {
?><div class="wrap">
	<h2><?php _e('Instant Affiliate Program Brand Management - Edit a Brand',IAS_TEXTDOMAIN); ?></h2>
	<p><?php _e('Welcome to the Edit Brand Interface. From here you will be able to edit your brands.',IAS_TEXTDOMAIN); ?></p>
	<p><?php _e('For more information on how to use this interface, visit:',IAS_TEXTDOMAIN); ?> <a href="http://rm.14all.me/projects/instant-affiliate-software-ias-plugin/wiki/Brand_Management_Interface#How-to-Use" class="fancyopen" target="_blank">IAS Wiki - Brand Management Interface - How to Use</a></p>
	<form action="admin.php?page=ias-brands-edit-save&noheader=true" method="POST" role="form">
		<input type="hidden" name="id" value="<?php print($brand['id']); ?>" />
		<div style="width:100%; max-width: 40%;">
			<table class="widefat" role="table" cellpadding="0" cellspacing="0">
				<tbody>
					<tr>
						<th><?php _e('Brand Name', IAS_TEXTDOMAIN); ?></th>
						<td><input type="text" name="name" style="width:100%;" required value="<?php print($brand['name']); ?>" /></td>
					</tr>
					<tr>
						<th><?php _e('Brand URL', IAS_TEXTDOMAIN); ?></th>
						<td><input type="url" name="URL" style="width:100%;" required value="<?php print($brand['URL']); ?>" /></td>
					</tr>
					<tr>
						<th><?php _e('Login by Credentials URL', IAS_TEXTDOMAIN); ?></th>
						<td><input type="url" name="loginByCredsURL" style="width:100%;" required value="<?php print($brand['loginByCredsURL']); ?>" /></td>
					</tr>
					<tr>
						<th><?php _e('URL of Logo', IAS_TEXTDOMAIN); ?></th>
						<td><input type="url" name="logoURL" style="width:100%;" required value="<?php print($brand['logoURL']); ?>" /></td>
					</tr>
					<tr>
						<th><?php _e('Campaign ID', IAS_TEXTDOMAIN); ?></th>
						<td><input type="number" min="0" name="campaignID" style="width:100%;" required value="<?php print($brand['campaignID']); ?>" /></td>
					</tr>
					<?php
						if($brand['isBDB'] == 1) {
						?><tr>
						<th><?php _e('API License Key', IAS_TEXTDOMAIN); ?></th>
						<td><input type="text" name="licenseKey" style="width:100%;" required value="<?php print($brand['licenseKey']); ?>" /></td>
					</tr>
						<?php
						} else {
						?><tr>
						<th><?php _e('API URL', IAS_TEXTDOMAIN); ?></th>
						<td><input type="url" name="apiURL" style="width:100%;" required value="<?php print($brand['apiURL']); ?>" /></td>
					</tr>
					<tr>
						<th><?php _e('API User', IAS_TEXTDOMAIN); ?></th>
						<td><input type="text" name="apiUser" style="width:100%;" required value="<?php print($brand['apiUser']); ?>" /></td>
					</tr>
					<tr>
						<th><?php _e('API Password', IAS_TEXTDOMAIN); ?></th>
						<td><input type="password" name="apiPass" style="width:100%;" required value="<?php print($brand['apiPass']); ?>" /></td>
					</tr>
						<?php
						}
					?>
					<tr>
						<th colspan="2"><input type="submit" class="button button-primary" value="<?php _e('Save',IAS_TEXTDOMAIN); ?>" /><a href="admin.php?page=ias-brands" class="button" style="margin-left: 10px;"><?php _e('Cancel',IAS_TEXTDOMAIN); ?></a></th>
					</tr>
				</tbody>
			</table>
		</div>
	</form>
</div>
<?php
	}
?>