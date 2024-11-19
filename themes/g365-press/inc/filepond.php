<?php

function g365_filepond() {
	
// 	require_once( get_template_directory_uri() . '/inc/filepond/index.html' );
  wp_enqueue_script( 'filepond', get_template_directory_uri() . '/inc/filepond/filepond.js', array('admin-jquery'), '1.1', true );
  wp_enqueue_script( 'filepond-preview', get_template_directory_uri() . '/inc/filepond/filepond-plugin-image-preview.js', array('admin-jquery', 'filepond'), '1.2', true );
  wp_enqueue_script( 'filepond-adapter', get_template_directory_uri() . '/inc/filepond/filepond-adapter.js', array('admin-jquery', 'filepond', 'filepond-preview'), '1.3', true );
  wp_enqueue_script( 'filepond-start', get_template_directory_uri() . '/inc/filepond/filepond-start.js', array('admin-jquery', 'filepond', 'filepond-preview', 'filepond-adapter'), '1.4', true );
  wp_enqueue_style( 'filepond-admin-preview-css', get_template_directory_uri() . '/inc/filepond/filepond-plugin-image-preview.css', true );
  wp_enqueue_style( 'filepond-admin-css', get_template_directory_uri() . '/inc/filepond/filepond.css', true );

?>

<input
type="file" 
class="filepond"
name="filepond" 
/>

<?php } ?>