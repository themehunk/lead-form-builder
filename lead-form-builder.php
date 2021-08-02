<?php
/*
  Plugin Name: Lead Form Builder
  Description: Lead Form Builder Plugin is a contact form builder as well as lead generator. This contact form is compatible with all page builders like Elementor, Brizy, SiteOrigin, Gutenburg etc. Plugin comes with nearly all field options required to create Contact form, Registration form, News letter and contain Ajax based drag & drop field ordering.
  Version: 1.5.9
  Author: ThemeHunk
  Text Domain: lead-form-builder
  Author URI: http://www.themehunk.com/
 */

  if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
  
// Version constant for easy CSS refreshes
define('LFB_VER', '1.5.8');

define('LFB_PLUGIN_URL', plugin_dir_url(__FILE__));

include_once( plugin_dir_path(__FILE__) . 'inc/lfb-constant.php' );

/**
 * Add the settings link to the Lead Form Plugin plugin row
 *
 * @param array $links - Links for the plugin
 * @return array - Links
 */
function lfb_plugin_action_links($links) {
  $settings_page = add_query_arg(array('page' => 'wplf-plugin-menu'), admin_url());
  $settings_link = '<a href="'.esc_url($settings_page).'">'.__('Settings', 'lead-form-builder' ).'</a>';
  array_unshift($links, $settings_link);

   $links['lfb_pro'] = sprintf( '<a style="color: #39b54a; font-weight: 700;" href="%s" target="_blank" class="lfb-plugins-gopro">%s</a>', 'https://themehunk.com/product/lead-form-builder-pro', __( 'Go Pro', 'lead-form-builder' ) );
  return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'lfb_plugin_action_links', 10, 1);

include_once( plugin_dir_path(__FILE__) . 'inc/lf-db.php' );

register_activation_hook(__FILE__, 'lfb_plugin_activate');
if(!function_exists('lfb_include_file')) {
function lfb_include_file(){
include_once( plugin_dir_path(__FILE__) . 'inc/inc.php' );
}
add_action('init','lfb_include_file');
}
include_once( plugin_dir_path(__FILE__) . 'inc/lfb-widget.php' );
include_once( plugin_dir_path(__FILE__) . 'elementor/lfb-addon-elementor.php' );
// show notify
include_once(plugin_dir_path(__FILE__) . 'notify/notify.php' );

?>