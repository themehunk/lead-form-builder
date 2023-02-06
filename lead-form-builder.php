<?php
/*
  Plugin Name: Lead Form Builder
  Description: Lead Form Builder Plugin is a contact form builder as well as lead generator. This contact form is compatible with all page builders like Elementor, Brizy, SiteOrigin, Gutenburg etc. Plugin comes with nearly all field options required to create Contact form, Registration form, News letter and contain Ajax based drag & drop field ordering. <a href="https://themehunk.com/plugins/" target="_blank">Get more plugins for your website on <strong>ThemeHunk</strong></a>
  Version: 1.8.1
  Author: ThemeHunk
  Text Domain: lead-form-builder
  Author URI: http://www.themehunk.com/
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
// Version constant for easy CSS refreshes

if (!function_exists('lfb_plugin_action_links')){

define('LFB_VER', '1.8.1');

define('LFB_PLUGIN_URL', plugin_dir_url(__FILE__));
include_once(plugin_dir_path(__FILE__) . 'inc/themehunk-menu/admin-menu.php');

include_once( plugin_dir_path(__FILE__) . 'inc/lfb-constant.php' );

/**
 * Add the settings link to the Lead Form Plugin plugin row
 *
 * @param array $links - Links for the plugin
 * @return array - Links
 */
function lfb_plugin_action_links($links){
  $settings_page = add_query_arg(array('page' => 'wplf-plugin-menu'), admin_url('/admin.php?'));
  $settings_link = '<a href="'.esc_url($settings_page).'">'.__('Settings', 'lead-form-builder' ).'</a>';
  array_unshift($links, $settings_link);
  return $links;
}
add_filter('plugin_action_links_'.plugin_basename(__FILE__), 'lfb_plugin_action_links', 10, 1);

add_filter('plugin_row_meta', 'lfb_plugin_meta_links', 10, 2);


/**
   * Add links to plugin's description in plugins table
   *
   * @param array  $links  Initial list of links.
   * @param string $file   Basename of current plugin.
   *
   * @return array
   */
if ( ! function_exists( 'lfb_plugin_meta_links' ) ){

  function lfb_plugin_meta_links($links, $file){

    if ($file !== plugin_basename(__FILE__)) {
      return $links;
    }

    $demo_link = '<a target="_blank" href="https://wpthemes.themehunk.com/lead-form-builder-pro/" title="' . __('Live Demo', 'lead-form-builder') . '"><span class="dashicons  dashicons-laptop"></span>' . __('Live Demo', 'lead-form-builder') . '</a>';

    $doc_link = '<a target="_blank" href="https://themehunk.com/docs/lead-form/" title="' . __('Documentation', 'lead-form-builder') . '"><span class="dashicons  dashicons-search"></span>' . __('Documentation', 'lead-form-builder') . '</a>';

    $support_link = '<a target="_blank" href="https://themehunk.com/contact-us/" title="' . __('Support', 'lead-form-builder') . '"><span class="dashicons  dashicons-admin-users"></span>' . __('Support', 'lead-form-builder') . '</a>';

    $pro_link = '<a target="_blank" href="https://themehunk.com/product/lead-form-builder-pro/" title="' . __('Premium Version', 'lead-form-builder') . '"><span class="dashicons  dashicons-cart"></span>' . __('Premium Version', 'lead-form-builder') . '</a>';

    $links[] = $demo_link;
    $links[] = $doc_link;
    $links[] = $support_link;
    $links[] = $pro_link;

    return $links;

  } // plugin_meta_links

}
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
include_once( plugin_dir_path(__FILE__) . 'notify/notify.php' );
}



//$current_user = wp_get_current_user();


 print_r(ABSPATH );


 //Array ( [path] => C:\xampp\htdocs\wp572/wp-content/uploads/2022/09 [url] => http://localhost/wp572/wp-content/uploads/2022/09 [subdir] => /2022/09 [basedir] => C:\xampp\htdocs\wp572/wp-content/uploads [baseurl] => http://localhost/wp572/wp-content/uploads [error] => )


// if ( isset( $current_user->user_login ) && ! empty( $upload_dir['basedir'] ) ) {
//     // $user_dirname = $upload_dir['basedir'].'/'.$current_user->user_login;
//     //     if ( ! file_exists( $user_dirname ) ) {
// 	  //   wp_mkdir_p( $user_dirname );
//     // }
// }


//plugin_slug_download('https://github.com/themehunk/th-shop-mania/archive/refs/heads/main.zip');
include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );

$slug = 'https://github.com/themehunk/th-shop-mania/archive/refs/heads/main.zip';

$install_url = add_query_arg(array(
  'action' => 'activate',
  'plugin' => rawurlencode( $slug ),
  'plugin_status' => 'all',
  'paged' => '1',
 // '_wpnonce' => wp_create_nonce('activate-plugin_' ),
), network_admin_url('plugins.php'));

print_r($install_url);


function plugin_slug_download($remote_file_url){
  $upload_dir   = wp_upload_dir();
  $local_dir = $upload_dir['basedir'];
  $copy = copy( $remote_file_url, $local_dir );
  
  $sucess = ( !$copy )?false:true;
    return $sucess;
  }
  
  
  function plugin_slug_unzip($filepath){
  
    $path = pathinfo( realpath( $filepath ), PATHINFO_DIRNAME );
  
        $sucess = false;
        $zip = new ZipArchive;
        $res = $zip->open($filepath);
        if ($res === TRUE) {
            $zip->extractTo( $path );
            $zip->close();
            $sucess = true;
        }
        else {
            $sucess = false;
        }
  
      return $sucess;
  
  }
  
  function plugin_slug_rename($old_path,$newfile,$name){
     rename( $old_path."/".$newfile, $name) ;
  }
  
  function plugin_slug_unlink($unlink){
      unlink($unlink);
  }
  