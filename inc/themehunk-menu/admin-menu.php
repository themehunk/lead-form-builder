<?php
if ( ! function_exists( 'themehunk_admin_menu' ) ) {
    include_once( ABSPATH . 'wp-admin/includes/plugin-install.php' );
    define( 'THEMEHUNK_PURL', plugin_dir_url( __FILE__ ) );
    define( 'THEMEHUNK_PDIR', plugin_dir_path( __FILE__ ) );

    include_once THEMEHUNK_PDIR . '/th-option/th-option.php';

    add_action( 'admin_menu', 'themehunk_admin_menu' );
    add_action( 'admin_enqueue_scripts', 'admin_scripts' );

    function themehunk_admin_menu() {
        add_menu_page(
            __( 'ThemeHunk', 'lead-form-builder' ),
            __( 'ThemeHunk', 'lead-form-builder' ),
            'manage_options',
            'themehunk-plugins',
            'themehunk_plugins',
            LFB_PLUGIN_URL . 'images/icon.svg',
            59
        );
    }

    function themehunk_plugins() {
        $obj = new themehunk_plugin_option();
        $obj->tab_page();
    }

    function admin_scripts( $hook ) {
        if ( $hook === 'toplevel_page_themehunk-plugins' ) {
            wp_enqueue_style( 'themehunk-plugin-css', THEMEHUNK_PURL . '/th-option/assets/css/started.css' );
            wp_enqueue_script( 'themehunk-plugin-js', THEMEHUNK_PURL . '/th-option/assets/js/th-options.js', array( 'jquery', 'updates' ), '1', true );
            wp_localize_script( 'themehunk-plugin-js', 'bajax', array(
                'ajaxurl'      => admin_url( 'admin-ajax.php' ),
                'requestNonce' => wp_create_nonce( 'vrifyth-nonce' ),
            ) );
            wp_localize_script( 'themehunk-plugin-js', 'thMarket', array(
                'loading' => __( 'Loading plugins...', 'lead-form-builder' ),
                'error'   => __( 'Something went wrong. Please refresh the page.', 'lead-form-builder' ),
            ) );
        }
    }
}

/*
 * Plugin install / activate
 */
if ( ! function_exists( 'th_activeplugin' ) ) {
    add_action( 'wp_ajax_th_activeplugin', 'th_activeplugin' );

    function th_activeplugin() {
        $nonce = isset( $_POST['requestNonce'] ) ? $_POST['requestNonce'] : '';

        if ( ! is_user_logged_in() || ! current_user_can( 'administrator' ) ) {
            wp_die( -1, 403 );
        }
        if ( ! current_user_can( 'manage_options' ) || ! wp_verify_nonce( $nonce, 'vrifyth-nonce' ) ) {
            exit;
        }
        if ( ! isset( $_POST['init'] ) || ! $_POST['init'] ) {
            wp_send_json_error( array( 'success' => false, 'message' => __( 'No plugin specified', 'lead-form-builder' ) ) );
        }

        $plugin_init = esc_attr( $_POST['init'] );
        $activate    = activate_plugin( $plugin_init );

        if ( is_wp_error( $activate ) ) {
            wp_send_json_error( array( 'success' => false, 'message' => $activate->get_error_message() ) );
        }
        wp_send_json_success( array( 'success' => true, 'message' => __( 'Plugin Successfully Activated', 'lead-form-builder' ) ) );
    }
}

/*
 * Filter plugins by status (all | installed | not-installed)
 */
if ( ! function_exists( 'th_filter_plugins' ) ) {
    add_action( 'wp_ajax_th_filter_plugins', 'th_filter_plugins' );

    function th_filter_plugins() {
        check_ajax_referer( 'vrifyth-nonce', 'requestNonce' );
        if ( ! current_user_can( 'manage_options' ) ) wp_die( -1, 403 );

        $obj = new themehunk_plugin_option();
        $obj->filter_plugins_ajax();
    }
}
