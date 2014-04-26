<div class="wrap">
	<h2><?php _e('Instant Affiliate Program Bug Submission',IAS_TEXTDOMAIN); ?></h2>
	<p><?php _e('Welcome to the bug submission form.',IAS_TEXTDOMAIN); ?><?php _e('Using this form you will be able to let the IAS Development team know about any issues that you have using the software.',IAS_TEXTDOMAIN); ?></p>
	<p><?php _e('In order to properly assist you, we will be collecting the following information automatically:',IAS_TEXTDOMAIN); ?>
		<ul style="list-style-type: disc; padding-left: 20px;">
			<li><?php _e('Web Server (Apache / Nginx) Version',IAS_TEXTDOMAIN); ?></li>
			<li><?php _e('PHP Version',IAS_TEXTDOMAIN); ?></li>
			<li><?php _e('MySQL Version',IAS_TEXTDOMAIN); ?></li>
			<li><?php _e('WordPress Site Title',IAS_TEXTDOMAIN); ?></li>
			<li><?php _e('WordPress Version',IAS_TEXTDOMAIN); ?></li>
			<li><?php _e('WordPress Admin Email',IAS_TEXTDOMAIN); ?></li>
			<li><?php _e('Server IP',IAS_TEXTDOMAIN); ?></li>
			<li><?php _e('Server External IP',IAS_TEXTDOMAIN); ?></li>
		</ul>
	</p>
	<p><?php _e('If you do not want this information to be collected, please contact your IAS distributer.',IAS_TEXTDOMAIN); ?></p>
	<form action="admin.php?page=ias-bugs-save&noheader=true" method="POST" role="form">
		<div style="clear:both; float:none; width: 100%;">
			<div style="float:left; width:50%;">
				<table class="widefat" role="table">
					<thead>
						<tr>
							<th><?php _e('Bug Submission Form',IAS_TEXTDOMAIN); ?></th>
						</tr>
					</thead>
					 <tbody>
					 	<tr><?php
					 		if(isset($_GET['success']) && $_GET['success'] == 1) {
					 	?>
					 		<td><?php _e('Bug Submitted Successfully',IAS_TEXTDOMAIN); ?></td>
					 	<?php
					 		}
					 		else if(isset($_GET['success']) && $_GET['success'] == 0) {
					 	?>
					 		<td>
					 			<?php _e('Bug was not submitted successfully. Please try again',IAS_TEXTDOMAIN); ?>
					 			<textarea name="bug_contents" style="width: 100%; height: 250px;"></textarea>
					 		</td>
					 	<?php
					 		}
					 		else {
					 	?>
					 		<td>
					 			<textarea name="bug_contents" style="width: 100%; height: 250px;"></textarea>
					 		</td>
					 	<?php
					 		}
					 	?>
					 	</tr>
					 </tbody>
					 <tfoot>
					 	<tr>
					 		<th>
					 			<input type="submit" value="<?php _e('Submit',IAS_TEXTDOMAIN); ?>" class="button button-primary" />
					 		</th>
					 	</tr>
					 </tfoot>
				</table>
			</div>
		</div>
	</form>
</div>