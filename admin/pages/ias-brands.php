<div class="wrap">
	<h2><?php _e('Instant Affiliate Program Brand Management',IAS_TEXTDOMAIN); ?></h2>
	<p><?php _e('Welcome to the Brand Management Interface. From here you will be able to manage all the brands which your site is integrated with.',IAS_TEXTDOMAIN); ?></p>
	<p><?php _e('For more information on how to use this interface, visit:',IAS_TEXTDOMAIN); ?> <a href="http://rm.14all.me/projects/instant-affiliate-software-ias-plugin/wiki/Brand_Management_Interface#How-to-Use" class="fancyopen" target="_blank">IAS Wiki - Brand Management Interface - How to Use</a></p>
	<hr />
	<?php
		$table_data = $wpdb->get_results( ias_fix_db_prefix( "SELECT * FROM `{{ias}}brands`" ) , ARRAY_A );
		$table_fields = array(
			'id' => array(
				'name' => '&nbsp;',
				'type' => 'bool',
				'isLink' => FALSE,
			),
			'active' => array(
				'name' => 'Active',
				'type' => 'view-bool',
				'isLink' => FALSE,
			),
			'logoURL' => array(
				'name' => 'Logo',
				'type' => 'image',
				'isLink' => TRUE,
			),
			'name' => array(
				'name' => 'Brand Name',
				'type' => 'view-text',
				'isLink' => TRUE,
			),
			'URL' => array(
				'name' => 'Brand Site',
				'type' => 'view-url',
				'isLink' => TRUE,
			),
		);
		$table_untouchables = array(
			1,
			2,
			3,
		);
		$table = new ias_table($table_data, $table_fields);
		$table->setProperty('untouchables',$table_untouchables);
		$table->setProperty('untouchablesKey','id');
		$table->setProperty('table_title','Integrated Brands');
		$table->setProperty('searchKey','name');
		$table->setProperty('isForm',TRUE);
		$table->setProperty('deleteAction','admin.php?page=ias-brands-save&noheader=true');
		$table->setProperty('addAction','admin.php?page=ias-brands-add');
		$table->setProperty('editAction','admin.php?page=ias-brands-edit');
		print($table->html);
	?>
</div>