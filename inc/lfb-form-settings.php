<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function lfb_form_settings_page() {
    if ( ! current_user_can( 'manage_options' ) ) return;

    $form_id = isset( $_GET['form_id'] ) ? intval( $_GET['form_id'] ) : 0;
    if ( ! $form_id ) {
        echo '<div class="wrap"><div class="notice notice-error"><p>' . __( 'No form selected.', 'lead-form-builder' ) . ' <a href="' . esc_url( admin_url( 'admin.php?page=wplf-plugin-menu' ) ) . '">' . __( 'Go back to forms', 'lead-form-builder' ) . '</a>.</p></div></div>';
        return;
    }

    global $wpdb;
    $th_save_db = new LFB_SAVE_DB( $wpdb );
    $table_name = LFB_FORM_FIELD_TBL;
    $prepare    = $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d LIMIT 1", $form_id );
    $posts      = $th_save_db->lfb_get_form_content( $prepare );

    if ( ! $posts ) {
        echo '<div class="wrap"><div class="notice notice-error"><p>' . __( 'Form not found.', 'lead-form-builder' ) . ' <a href="' . esc_url( admin_url( 'admin.php?page=wplf-plugin-menu' ) ) . '">' . __( 'Go back to forms', 'lead-form-builder' ) . '</a>.</p></div></div>';
        return;
    }

    $mail_setting      = $posts[0]->mail_setting;
    $usermail_setting  = $posts[0]->usermail_setting;
    $captcha_option    = $posts[0]->captcha_status;
    $lead_store_option = $posts[0]->storeType;

    $active_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'email';

    $tabs = array(
        'email'   => array( 'label' => __( 'Email Notification', 'lead-form-builder' ), 'icon' => lfb_svg( 'mail', 18 ),           'pro' => false ),
        'captcha' => array( 'label' => __( 'Spam Protection', 'lead-form-builder' ),    'icon' => lfb_svg( 'shield-current', 18 ), 'pro' => false ),
        'general' => array( 'label' => __( 'Form Settings', 'lead-form-builder' ),      'icon' => lfb_svg( 'settings', 18 ),       'pro' => false ),
        'premium' => array( 'label' => __( 'Premium Features', 'lead-form-builder' ),   'icon' => lfb_svg( 'star', 18 ),           'pro' => true  ),
    );

    $lf_email = new LFB_EmailSettingForm( $form_id );

    echo '<div class="wrap lfb-settings-wrap">';

    lfb_admin_menu_header( 'form-settings' );

    echo '<div class="lfb-settings-layout">';

    // Left sidebar
    echo '<nav class="lfb-settings-sidebar">';
    foreach ( $tabs as $key => $tab ) {
        $is_active  = ( $active_tab === $key );
        $active_cls = $is_active ? ' lfb-stab-active' : '';
        $pro_cls    = ! empty( $tab['pro'] ) ? ' lfb-stab-pro' : '';
        $tab_url    = admin_url( 'admin.php?page=lfb-form-settings&form_id=' . $form_id . '&tab=' . $key );

        if ( ! empty( $tab['pro'] ) ) {
            echo '<div class="lfb-stab-divider"></div>';
        }

        echo '<a href="' . esc_url( $tab_url ) . '" class="lfb-stab-item' . $active_cls . $pro_cls . '">';
        echo '<span class="lfb-stab-icon">' . $tab['icon'] . '</span>';
        echo '<span class="lfb-stab-label">' . $tab['label'] . '</span>';
        if ( ! empty( $tab['pro'] ) ) {
            echo '<span class="lfb-stab-crown">' . lfb_svg( 'star', 10 ) . '</span>';
        } elseif ( $is_active ) {
            echo '<span class="lfb-stab-arrow">' . lfb_svg( 'chevron-right' ) . '</span>';
        }
        echo '</a>';
    }
    echo '</nav>';

    // Content panel
    echo '<div class="lfb-settings-content">';

    switch ( $active_tab ) {

        case 'email':
            echo '<div class="lfb-stab-panel-title">
                <h2>' . __( 'Email Notification', 'lead-form-builder' ) . '</h2>
                <p>' . __( 'Configure who receives form submission notifications and what they receive.', 'lead-form-builder' ) . '</p>
            </div>';
            $lf_email->lfb_email_setting_form( $form_id, $mail_setting, $usermail_setting );
            break;

        case 'captcha':
            echo '<div class="lfb-stab-panel-title">
                <h2>' . __( 'Spam Protection', 'lead-form-builder' ) . '</h2>
                <p>' . __( 'Configure Google reCAPTCHA to prevent spam submissions on your form.', 'lead-form-builder' ) . '</p>
            </div>';
            $lf_email->lfb_captcha_setting_form( $form_id, $captcha_option );
            break;

        case 'general':
            echo '<div class="lfb-stab-panel-title">
                <h2>' . __( 'Form Settings', 'lead-form-builder' ) . '</h2>
                <p>' . __( 'Configure form lead storage, validation messages, and thank-you message.', 'lead-form-builder' ) . '</p>
            </div>';
            $lf_email->lfb_lead_setting_form( $form_id, $lead_store_option );
            break;

        case 'premium':
            echo '<div class="lfb-stab-panel-title">
                <h2>' . __( 'Premium Features', 'lead-form-builder' ) . '</h2>
                <p>' . __( 'Unlock powerful add-ons and integrations with Lead Form Builder Pro.', 'lead-form-builder' ) . '</p>
            </div>';

            echo '<div class="lfb-premium-hero">
                <div class="lfb-premium-hero__icon">' . lfb_svg( 'star', 32 ) . '</div>
                <div class="lfb-premium-hero__text">
                    <h3>' . __( 'Upgrade to Pro', 'lead-form-builder' ) . '</h3>
                    <p>' . __( 'Get access to powerful integrations, API tools, and advanced sign-in options.', 'lead-form-builder' ) . '</p>
                </div>
                <a href="https://www.themehunk.com/lead-form-builder-pro/" target="_blank" class="lfb-premium-upgrade-btn">
                    ' . lfb_svg( 'star', 14 ) . ' ' . __( 'Get Pro Now', 'lead-form-builder' ) . '
                </a>
            </div>';

            $features = array(
                array(
                    'icon'  => lfb_svg( 'mail', 28 ),
                    'color' => 'blue',
                    'title' => __( 'MailChimp Integration', 'lead-form-builder' ),
                    'desc'  => __( 'Automatically subscribe leads to your MailChimp lists on form submission.', 'lead-form-builder' ),
                ),
                array(
                    'icon'  => lfb_svg( 'settings', 28 ),
                    'color' => 'purple',
                    'title' => __( 'SMTP Email Settings', 'lead-form-builder' ),
                    'desc'  => __( 'Configure custom SMTP for reliable transactional email delivery.', 'lead-form-builder' ),
                ),
                array(
                    'icon'  => lfb_svg( 'shield-current', 28 ),
                    'color' => 'amber',
                    'title' => __( 'API Key Manager', 'lead-form-builder' ),
                    'desc'  => __( 'Generate and manage API keys for headless or third-party integrations.', 'lead-form-builder' ),
                ),
                array(
                    'icon'  => lfb_svg( 'star', 28 ),
                    'color' => 'green',
                    'title' => __( 'Google Sign-In', 'lead-form-builder' ),
                    'desc'  => __( 'Let users pre-fill the form using their Google account with one click.', 'lead-form-builder' ),
                ),
                array(
                    'icon'  => lfb_svg( 'upload', 28 ),
                    'color' => 'teal',
                    'title' => __( 'Export Leads to CSV', 'lead-form-builder' ),
                    'desc'  => __( 'Select a date range and download all form submissions as a CSV file.', 'lead-form-builder' ),
                ),
                array(
                    'icon'  => lfb_svg( 'upload-cloud', 28 ),
                    'color' => 'pink',
                    'title' => __( 'Form Export / Import', 'lead-form-builder' ),
                    'desc'  => __( 'Backup, restore or migrate forms using JSON-based export and import.', 'lead-form-builder' ),
                ),
            );

            echo '<div class="lfb-premium-grid">';
            foreach ( $features as $feat ) {
                echo '<div class="lfb-premium-card">
                    <div class="lfb-premium-card__lock">' . lfb_svg( 'lock', 22 ) . '</div>
                    <div class="lfb-premium-card__icon lfb-pci--' . esc_attr( $feat['color'] ) . '">' . $feat['icon'] . '</div>
                    <div class="lfb-premium-card__body">
                        <h4>' . esc_html( $feat['title'] ) . '</h4>
                        <p>' . esc_html( $feat['desc'] ) . '</p>
                    </div>
                    <span class="lfb-premium-card__badge">' . __( 'Pro', 'lead-form-builder' ) . '</span>
                </div>';
            }
            echo '</div>';

            echo '<div class="lfb-premium-footer">
                <p>' . __( 'All Pro features include priority support and lifetime updates.', 'lead-form-builder' ) . '</p>
                <a href="https://www.themehunk.com/lead-form-builder-pro/" target="_blank" class="lfb-premium-upgrade-btn lfb-premium-upgrade-btn--outline">
                    ' . __( 'View All Features &rarr;', 'lead-form-builder' ) . '
                </a>
            </div>';
            break;

        default:
            echo '<p>' . __( 'Tab not found.', 'lead-form-builder' ) . '</p>';
    }

    echo '</div>'; // .lfb-settings-content
    echo '</div>'; // .lfb-settings-layout
    echo '</div>'; // .wrap
}
