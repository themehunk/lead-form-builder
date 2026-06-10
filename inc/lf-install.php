<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
 * Include assets
 */
function lfb_admin_assets($hook) {
    $pageSearch = array('admin_page_add-new-form','admin_page_all-form-leads','themehunk_page_wplf-plugin-menu','admin_page_pro-form-leads','admin_page_lfb-form-settings');
    if(in_array($hook, $pageSearch)){
        wp_enqueue_style('wpth_fa_css', LFB_PLUGIN_URL . 'font-awesome/css/font-awesome.css');
        wp_enqueue_style('lfb-option-css', LFB_PLUGIN_URL . 'css/option-style.css');
        wp_enqueue_style('sweet-dropdown.min', LFB_PLUGIN_URL . 'css/jquery.sweet-dropdown.min.css');
        wp_enqueue_style('wpth_b_css', LFB_PLUGIN_URL . 'css/b-style.css');
        wp_enqueue_script('lfb_modernizr_js', LFB_PLUGIN_URL . 'js/modernizr.js', '', LFB_VER, true);
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script("jquery-ui-sortable");
        wp_enqueue_script("jquery-ui-draggable");
        wp_enqueue_script("jquery-ui-droppable");
        wp_enqueue_script("jquery-ui-accordion");
        wp_enqueue_style( 'jquery-ui' );
        wp_enqueue_script('alpha-color-picker', LFB_PLUGIN_URL . 'js/alpha-color-picker.js', array('jquery'), LFB_VER, true);
        wp_enqueue_script('lfb_upload', LFB_PLUGIN_URL . 'js/upload.js', '', LFB_VER, true);
        wp_enqueue_script('sweet-dropdown.min', LFB_PLUGIN_URL . 'js/jquery.sweet-dropdown.min.js', '', LFB_VER, true);

        wp_enqueue_script('lfb_b_js', LFB_PLUGIN_URL . 'js/b-script.js', array('jquery'), LFB_VER, true);
        wp_localize_script( 'lfb_b_js', 'backendajax', array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'lfb_secure_nonce' ),
        ) );
    }

    // Enqueue React design panel on the form preview/design page
    if (
        isset( $_GET['page'] )   && $_GET['page']   === 'wplf-plugin-menu' &&
        isset( $_GET['action'] ) && $_GET['action']  === 'show' &&
        isset( $_GET['formid'] ) && is_numeric( $_GET['formid'] )
    ) {
        wp_enqueue_style( 'lfb_f_css', LFB_PLUGIN_URL . 'css/f-style.css' );

        $fid        = intval( $_GET['formid'] );
        $asset_file = LFB_BASE_DIR_PATH . 'block/build/admin-panel.asset.php';

        if ( file_exists( $asset_file ) ) {
            $asset = include $asset_file;

            wp_enqueue_style(
                'lfb-design-panel-css',
                LFB_PLUGIN_URL . 'block/build/style-admin-panel.css',
                array( 'wp-components' ),
                $asset['version']
            );

            if ( function_exists( 'wp_enqueue_media' ) ) {
                wp_enqueue_media();
            }

            wp_enqueue_script(
                'lfb-design-panel-js',
                LFB_PLUGIN_URL . 'block/build/admin-panel.js',
                $asset['dependencies'],
                $asset['version'],
                true
            );

            // Defaults that match f-style.css exactly — used when no custom design is saved
            $frontend_defaults = array(
                'colorid'                    => $fid,
                'lfb_form_width'             => 100,
                'lfb_form_border_width'      => 0,
                'lfb_form_border_style'      => 'none',
                'lfb_form_border_color'      => '#cccccc',
                'lfb_form_border_radius'     => 0,
                'lfb_form_box_shadow'        => 'none',
                'lfb_header_image'           => '',
                'lfb_color_heading'          => '#111111',
                'lfb_heading_alignment'      => 'left',
                'lfb_heading_hide'           => 'block',
                'lfb_heading_font_size'      => 26,
                'lfb_heading_position'       => 'default',
                'lfb_header_algmnt_tb'       => 0,
                'lfb_header_algmnt_lr'       => 0,
                'lfb_color_header_overlay'   => 'rgba(0,0,0,0)',
                'lfb_header_backdrop_blur'   => 0,
                'lfb_bg_image'               => '',
                'lfb_color_bg'               => 'rgba(255,255,255,0)',
                'lfb_bg_backdrop_blur'       => 0,
                'lfb_form_padding_top'       => 2,
                'lfb_form_padding_bottom'    => 2,
                'lfb_form_padding_left'      => 2,
                'lfb_form_padding_right'     => 2,
                'lfb_color_label'            => '#374151',
                'lfb_color_field_border'     => '#e0e0e0',    // f-style: border: 1px solid #e0e0e0
                'lfb_field_border_width'     => 1,
                'lfb_field_border_style'     => 'solid',
                'lfb_field_border_radius'    => 8,            // f-style: border-radius: 8px
                'lfb_color_field_bg'         => '#ffffff',
                'lfb_color_field_placeholder'=> '#555555',    // f-style: color: #555
                'lfb_req_star_color'         => '#e53e3e',
                'lfb_req_star_size'          => 14,
                'lfb_icon_bg'                => '#7b61ff',
                'lfb_choice_checked_color'   => '#7b61ff',
                'lfb_color_button_text'      => '#ffffff',
                'lfb_color_button_bg'        => '#0C0C10',    // f-style: background: #0C0C10
                'lfb_color_button_bg_hover'  => '#333333',
                'lfb_color_button_border'    => '#000000',    // f-style: border-color: #000
                'lfb_btn_border_width'       => 1,
                'lfb_btn_border_style'       => 'solid',
                'lfb_btn_border_radius'      => 3,            // f-style: border-radius: 3px
                'lfb_button_aligment'        => 'left',
                'lfb_button_font_size'       => 14,           // f-style: font-size: 14px
                'lfb_btn_padding_tb'         => 2,            // 2% ≈ 10px at 500px container (matches React default)
                'lfb_btn_padding_lr'         => 35,           // 35% width (matches React default; 0 = auto in PHP)
                'lfb_field_columns'          => '1',
                'lfb_custom_css'             => '',
            );

            $lfbdb_panel  = new LFB_SAVE_DB();
            $colordata    = $lfbdb_panel->lfb_get_colors_data( $fid );
            $colors_array = $frontend_defaults; // start with front-end defaults

            if ( isset( $colordata[0]->colorData ) && ! empty( $colordata[0]->colorData ) ) {
                $saved = maybe_unserialize( $colordata[0]->colorData );
                if ( is_array( $saved ) ) {
                    // Merge saved settings over defaults so missing keys still resolve
                    $colors_array = array_merge( $frontend_defaults, $saved );
                }
            }

            wp_localize_script( 'lfb-design-panel-js', 'lfbDesignPanel', array(
                'formId'   => $fid,
                'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'lfb_secure_nonce' ),
                'settings' => $colors_array,
            ) );
        }
    }

}
add_action('admin_enqueue_scripts', 'lfb_admin_assets');

function lfb_page_has_form() {
    global $post;
    if ( ! is_a( $post, 'WP_Post' ) ) {
        return false;
    }
    if ( has_shortcode( $post->post_content, 'lead-form' ) || has_block( 'themehunk/lead-form-builder', $post ) ) {
        return true;
    }
    // Detect lead form placed via Elementor (widget or shortcode widget stored in _elementor_data meta)
    if ( did_action( 'elementor/loaded' ) ) {
        $elementor_data = get_post_meta( $post->ID, '_elementor_data', true );
        if ( ! empty( $elementor_data ) && (
            strpos( $elementor_data, 'lead-form-styler' ) !== false ||
            strpos( $elementor_data, '[lead-form' ) !== false
        ) ) {
            return true;
        }
    }
    return false;
}

function lfb_wp_assets() {
    if ( ! lfb_page_has_form() ) {
        return;
    }
    wp_enqueue_style('lfb_f_css', LFB_PLUGIN_URL . 'css/f-style.css');
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('lfb_f_js', LFB_PLUGIN_URL . 'js/f-script.js', array('jquery'), LFB_VER, true);
    wp_localize_script('lfb_f_js', 'frontendajax', array(
        'ajaxurl'      => admin_url('admin-ajax.php'),
        '_wpnonce'     => wp_create_nonce( 'lfb_front_nonce' ),
        'required_msg' => get_option( 'lfb_required_field_msg', __( 'The field is required.', 'lead-form-builder' ) ),
        'error_msg'    => get_option( 'lfb_general_error_msg',  __( 'One or more fields have an error. Please check and try again.', 'lead-form-builder' ) ),
        'email_msg'    => get_option( 'lfb_email_field_msg',    __( 'Please enter a valid email address.', 'lead-form-builder' ) ),
    ));
    wp_enqueue_style('font-awesome', LFB_PLUGIN_URL . 'font-awesome/css/font-awesome.css');
}
add_action('wp_enqueue_scripts', 'lfb_wp_assets', 15);
/*
 * Register custom menu pages.
 */
function lfb_register_my_custom_menu_page() {

$user = get_userdata( get_current_user_id() );
// Get all the user roles as an array.
$user_roles = $user->roles;
add_submenu_page( 'themehunk-plugins', __('Lead Form Builder', 'wppb'), __('Lead Form Builder', 'wppb'), 'manage_options', 'wplf-plugin-menu','lfb_lead_form_page');
    add_submenu_page(false, __('Add Forms', 'lead-form-builder'), __('Add Forms', 'lead-form-builder'), 'manage_options', 'add-new-form', 'lfb_add_contact_forms');
    if( in_array( 'administrator', $user_roles, true )) {
    add_submenu_page(false, __('View Leads', 'lead-form-builder'), __('View Leads', 'lead-form-builder'), 'manage_options', 'all-form-leads', 'lfb_all_forms_lead');
    }
    add_submenu_page(false, __('Premium Version', 'th-lead-form'), __('Premium Version', 'th-lead-form'), 'manage_options', 'pro-form-leads', 'lfb_pro_feature');
    add_submenu_page(false, __('Form Settings', 'lead-form-builder'), __('Form Settings', 'lead-form-builder'), 'manage_options', 'lfb-form-settings', 'lfb_form_settings_page');


}
add_action('admin_menu', 'lfb_register_my_custom_menu_page');

function lfb_lead_form_page() {
    if (isset($_GET['action']) && isset($_GET['formid'])) {
        $form_action = sanitize_text_field($_GET['action']);
        $this_form_id = intval($_GET['formid']);
        $nonce = isset($_REQUEST['_wpnonce'])?$_REQUEST['_wpnonce']:false;


        if ($form_action == 'delete' && wp_verify_nonce($nonce, '_nonce_verify')) {
            $page_id =1;
            if (isset($_GET['page_id'])) {
            $page_id = intval($_GET['page_id']);
            }
            $th_edit_del_form = new LFB_EDIT_DEL_FORM();
            $th_edit_del_form->lfb_delete_form_content($form_action, $this_form_id,$page_id);
        }
        if ( $form_action == 'show' && isset( $_GET['formid'] ) ) {
            $fid       = intval( $_GET['formid'] );
            $lfbColors = new LFB_COLORS();
            $back_url  = esc_url( admin_url( 'admin.php?page=wplf-plugin-menu' ) );
            echo '<div class="lfb-design-page-wrap">';
            echo '<a href="' . $back_url . '" class="lfb-back-btn">' . lfb_svg( 'chevron-left' ) . ' ' . __( 'Back to Forms', 'lead-form-builder' ) . '</a>';
            echo $lfbColors->change_color();
            echo '<div class="lfb-form-preview-wrap">';
            echo do_shortcode( '[lead-form form-id="' . $fid . '" title=Contact Us]' );
            echo '</div>';
            echo '</div>';
            $lfbColors->lfb_color_form( $fid );
        }
        if ($form_action == 'today_leads') {
            $back_url = esc_url( admin_url( 'admin.php?page=wplf-plugin-menu' ) );
            echo '<div class="wrap">';
            echo '<a href="' . $back_url . '" class="lfb-back-btn">' . lfb_svg( 'chevron-left' ) . ' ' . __( 'Back to Forms', 'lead-form-builder' ) . '</a>';
            $th_show_today_leads = new LFB_Show_Leads();
            $th_show_today_leads->lfb_show_form_leads_datewise($this_form_id,"today_leads");
            echo '</div>';
        }
        if ($form_action == 'total_leads') {
            $back_url = esc_url( admin_url( 'admin.php?page=wplf-plugin-menu' ) );
            echo '<div class="wrap">';
            echo '<a href="' . $back_url . '" class="lfb-back-btn">' . lfb_svg( 'chevron-left' ) . ' ' . __( 'Back to Forms', 'lead-form-builder' ) . '</a>';
            $th_show_all_leads = new LFB_Show_Leads();
            $th_show_all_leads->lfb_show_form_leads_datewise($this_form_id,"total_leads");
            echo '</div>';
        }
    } else {
        $th_show_forms = new LFB_SHOW_FORMS();
        $page_id =1;
        if (isset($_GET['page_id'])) {
        $page_id = intval($_GET['page_id']);
        }
        $th_show_forms->lfb_show_all_forms($page_id);
    }
}

// extra slas remove
function lfb_array_stripslash($theArray){
   foreach ( $theArray as &$v ) if ( is_array($v) ) $v = lfb_array_stripslash($v); else $v = stripslashes($v);
   return $theArray;
}

// form builder update nad delete function
function lfb_add_contact_forms() {
    if (isset($_POST['update_form']) && wp_verify_nonce($_REQUEST['_wpnonce'],'_nonce_verify') ) {
    $update_form_id = intval($_POST['update_form_id']);
    $title = sanitize_text_field($_POST['post_title']);
    // Collect form_field_* keys directly (new field naming without lfb_form wrapper)
    $data_form = array();
    foreach ( $_POST as $key => $value ) {
        if ( strpos( $key, 'form_field_' ) === 0 && is_array( $value ) ) {
            $data_form[ $key ] = $value;
        }
    }
    global $wpdb;
    $table_name = LFB_FORM_FIELD_TBL;
    $update_leads = $wpdb->update( 
    $table_name,
    array( 
        'form_title' => $title,
      'form_data' => maybe_serialize(lfb_create_form_sanitize($data_form))
    ), 
    array( 'id' => $update_form_id ));
    $rd_url = admin_url( 'admin.php?page=add-new-form&action=edit&redirect=update&formid=' . $update_form_id );
    $complete_url = wp_nonce_url( $rd_url, '_nonce_verify' );
    wp_redirect( $complete_url );
    exit;
  }

if (isset($_GET['action']) && isset($_GET['formid'])) {
        $form_action = sanitize_text_field($_GET['action']);
        $this_form_id = intval($_GET['formid']);
        if ($form_action == 'edit') {
            $th_edit_del_form = new LFB_EDIT_DEL_FORM();
            $th_edit_del_form->lfb_edit_form_content($form_action, $this_form_id);
        }
    } else {
        $lf_add_new_form = new LFB_AddNewForm();
        $lf_add_new_form->lfb_add_new_form();
    }
}

function lfb_all_forms_lead() {
    $th_show_forms = new LFB_Show_Leads();
    $th_show_forms->lfb_show_form_leads();
}



function lfb_pro_feature(){

include_once( plugin_dir_path(__FILE__) . 'options/option.php' );

}
