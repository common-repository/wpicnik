<?php 
/*
Plugin Name: WPicnik
Plugin URI: http://onetruebrace.com/wpicnik
Description: Picnik integration now comes to WordPress. Fully compatible with WordPress 2.5.
Author: Quang Anh Do
Version: 1.0
Author URI: http://onetruebrace.com
*/

define('PICNIK_API_KEY', '8e6e4ca705cbe8956b07c7fd8b77d363');

add_filter('attachment_fields_to_edit', 'wpicnik_image_attachment_fields_to_edit', 10, 2);

function wpicnik_image_attachment_fields_to_edit($form_fields, $post) {
	if (substr($post->post_mime_type, 0, 5) == 'image') {		
		$import = wp_get_attachment_url($post->ID);
		$thumb = wp_get_attachment_thumb_url($post->ID);
		$export = get_bloginfo('wpurl') . '/wp-content/plugins/wpicnik/export.php?redirect=' . rawurlencode(get_bloginfo('wpurl') . '/wp-admin/upload.php');
		$css = get_bloginfo('wpurl') . '/wp-content/plugins/wpicnik/picnikbox.css'; 		
		$js = get_bloginfo('wpurl') . '/wp-content/plugins/wpicnik/picnikbox.js'; 		
		$close = get_bloginfo('wpurl') . '/wp-content/plugins/wpicnik/close.html'; 
		
		$url =  'http://www.picnik.com/service/?_apikey=' . PICNIK_API_KEY . 
				'&_import=' . rawurlencode($import) . 
				'&_imageid=' . $post->ID .
				'&_original_thumb=' . rawurlencode($thumb) .
				'&_export=' . rawurlencode($export) . 
				'&_export_method=POST&_export_agent=browser&_replace=confirm&_exclude=in,out' . 
				'&_close_target=' . rawurlencode($close);				

		$form_fields['picnik'] = array(
			'label' => __('Edit this image'),
			'input' => 'html',
			'html'  => "
				<a class='pbox' href='$url'>" . __('Start Picniking!') . "</a>
				<link rel='stylesheet' href='$css' media='screen' type='text/css' />
				<script type='text/javascript' src='$js'></script>
			",
		);

	}
	return $form_fields;
}

?>