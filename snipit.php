<?php
/*
Plugin Name: Snip.it
Plugin URI: https://snip.it/toolkit
Description: Integrate Snip.it with your WordPress blog
Version: 0.0.2
Author: Snip.it
Author URI: https://snip.it/
License: MIT
*/

/**
 * Version of the plugin's static assets; bump when asset changes
 */
define('SNIPIT_STATIC_VERSION', 0);

// Load and register the collection widget
require_once(plugin_dir_path( __FILE__ ).'snipit-collection-widget.php');
function snipit_collection_widget_register() {
  register_widget( 'Snipit_Collection_Widget' );
}
add_action( 'widgets_init', 'snipit_collection_widget_register' );


// Load and register the button shortcodes
require_once(plugin_dir_path( __FILE__ ).'snipit-button.php');
add_shortcode('snipit-button', array('Snipit_Button', 'RenderShortcode'));


function snipit_sharing_add_service($services) {
  if(class_exists('Sharing_Source', false)) {
    require_once(plugin_dir_path( __FILE__ ).'snipit-sharing.php');
    return Snipit_Sharing::InjectService($services);
  } else {
    return $services;
  }
}
function snipit_sharing_load() {
  wp_enqueue_style( 'snipit-sharing-admin', plugin_dir_url(__FILE__).'stylesheets/admin-sharing.css', false, SNIPIT_STATIC_VERSION );
  wp_enqueue_style( 'snipit-sharing', plugin_dir_url(__FILE__).'stylesheets/sharing.css', false, SNIPIT_STATIC_VERSION );
}
function snipit_sharing_head() {
  wp_enqueue_style( 'snipit-sharing', plugin_dir_url(__FILE__).'stylesheets/sharing.css', false, SNIPIT_STATIC_VERSION );
}
add_action('wp_head', 'snipit_sharing_head', 1);
add_action('load-settings_page_sharing', 'snipit_sharing_load');
add_filter('sharing_services', snipit_sharing_add_service, 200);
