<?php
/**
 * Display information related to display of pages
 */
if ( is_admin() ) {
    add_action( 'load-post.php', 'ias_add_must_login_meta' );
    add_action( 'load-post-new.php', 'ias_add_must_login_meta' );
    add_action( 'save_post', 'ias_save_must_login_meta', 10, 2 );
}

add_filter('the_content', 'ias_check_if_login_required');

function ias_add_must_login_meta() {
	add_action( 'add_meta_boxes', 'ias_add_must_login_meta_box' );
}

function ias_add_must_login_meta_box() {
	$boxes = array(
		array(
			'id' => 'page-requires-login',
			'title' => esc_html__( 'Login Settings' , IAS_TEXTDOMAIN ),
			'callback' => 'ias_add_must_login_box',
			'pageType' => 'post',
			'context' => 'side' , 
			'priority' => 'core',
		),
		array(
			'id' => 'page-requires-login',
			'title' => esc_html__( 'Login Settings' , IAS_TEXTDOMAIN ),
			'callback' => 'ias_add_must_login_box',
			'pageType' => 'page',
			'context' => 'side' , 
			'priority' => 'core',
		),
	);
	foreach ($boxes as $box) {
		add_meta_box( $box['id'] , $box['title'] , $box['callback'] , $box['pageType'] , $box['context'] , $box['priority'] );
	}
}

function ias_add_must_login_box( $object, $box ) {
	wp_nonce_field( basename( __FILE__ ), 'ias_add_must_login_box' );
	$fields = array(
		'form_action' => array(
			'type' => 'checkbox',
			'name' => 'page_requires_login',
			'label' => 'Page Content Requires Broker Login',
			'placeholder' => 'Page Content Requires Broker Login',
			'id' => 'page_requires_login',
			'value' => ( get_post_meta( $object->ID, 'page_requires_login', TRUE ) != '' ) ? get_post_meta( $object->ID, 'page_requires_login', TRUE ) : 0,
			'attributes' => NULL,
			),
	);
	$form = new ias_widget_form( $fields );
	print( $form->html );
}

function ias_save_must_login_meta( $post_id, $post ) {
	 if ( !isset( $_POST['ias_add_must_login_box'] ) || !wp_verify_nonce( $_POST['ias_add_must_login_box'], basename( __FILE__ ) ) ) {
	 	return $post_id;
	 }
	 $post_type = get_post_type_object( $post->post_type );
	 if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
	 	return $post_id;
	 }
	 $meta_value = get_post_meta( $post_id, 'page_requires_login' , TRUE );
	 if( $meta_value == '' ) {
		add_post_meta( $post_id, 'page_requires_login' , ( isset($_POST['page_requires_login']) ) ? $_POST['page_requires_login'] : '0' , TRUE );
	 } else {
		update_post_meta( $post_id, 'page_requires_login' , ( isset($_POST['page_requires_login']) ) ? $_POST['page_requires_login'] : '0' );
	 }
}

function ias_check_if_login_required( $content ) {
	$post_id = get_the_ID();
	if ( empty( $post_id ) ) {
		return $content;
	}
	$req_login = ( get_post_meta( $post_id, 'page_requires_login' , TRUE ) != '' ) ? get_post_meta( $post_id, 'page_requires_login' , TRUE ) : 0;
	if( $req_login == 1 || $req_login == '1' || $req_login == TRUE ) {
		if( isset( $_SESSION['ias_customer']) && $_SESSION['ias_customer']->valid == TRUE ) {
			return $content;
		} else {
			$html = '';
			$html .= '<div style="width: 100%">' . "\r\n";
			$html .= '	<h2>'. __('Sorry',IAS_TEXTDOMAIN) .'</h2>' . "\r\n";
			$html .= '	<p>' . __('In order to view this page, you must be logged in. Please log in below to access this page.' , IAS_TEXTDOMAIN ) . '</p>' . "\r\n";
			$html .= '	<div style="width: 350px; margin: 0 auto; margin-top: 20px; margin-bottom: 20px;">' . "\r\n";
			$html .= ias_login_form::shortcode('') . "\r\n";
			$html .= '	</div>' . "\r\n";
			$html .= '</div>' . "\r\n";
			return $html;
		}
	}
	else {
		return $content;
	}
}
?>