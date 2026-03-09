<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function lfb_header_page_manage( $type = '', $option = '' ) {

    $plus_svg  = lfb_svg( 'plus' );
    $form_svg  = lfb_svg( 'file', 28 );
    $back_svg  = lfb_svg( 'chevron-left', 16 );

    $page_title = '';
    $breadcrumb = '';

    if ( isset( $_GET['page'] ) && $_GET['page'] === 'wplf-plugin-menu' && ! isset( $_GET['action'] ) ) {
        $page_title = esc_html__( 'Lead Form Builder', 'lead-form-builder' );

    } elseif ( isset( $_GET['page'] ) && $_GET['page'] === 'all-form-leads' ) {
        $page_title = esc_html__( 'Form Leads', 'lead-form-builder' );
        $breadcrumb = 'View Leads';

    } elseif ( isset( $_GET['page'] ) && $_GET['page'] === 'pro-form-leads' ) {
        $page_title = esc_html__( 'Premium Plugin & Themes', 'lead-form-builder' );

    } elseif ( isset( $_GET['page'] ) && $_GET['page'] === 'add-new-form' ) {
        $page_title = esc_html__( 'Form Settings', 'lead-form-builder' );
        $breadcrumb = 'Form Settings';

    } elseif ( isset( $_GET['action'] ) && $_GET['action'] === 'show' ) {
        global $wpdb;
        $_fid   = isset( $_GET['formid'] ) ? intval( $_GET['formid'] ) : 0;
        $_ftitle = $_fid ? $wpdb->get_var( $wpdb->prepare( "SELECT form_title FROM {$wpdb->prefix}lead_form WHERE id = %d LIMIT 1", $_fid ) ) : '';
        $page_title = esc_html__( 'Form Preview', 'lead-form-builder' );
        $breadcrumb = $_ftitle ? esc_html( $_ftitle ) : 'Preview';

    } elseif ( isset( $_GET['action'] ) && $_GET['action'] === 'today_leads' ) {
        $page_title = esc_html__( 'Today Leads', 'lead-form-builder' );
        $breadcrumb = 'Today Leads';

    } elseif ( isset( $_GET['action'] ) && $_GET['action'] === 'total_leads' ) {
        $page_title = esc_html__( 'Total Leads', 'lead-form-builder' );
        $breadcrumb = 'Total Leads';
    }
    ?>

    <div class="lfb-header">
        <div class="lfb-header-top">
            <div class="lfb-header-left">
                <?php if ( $breadcrumb ) { ?>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=wplf-plugin-menu' ) ); ?>" class="lfb-back-btn" title="<?php esc_attr_e( 'Back to Forms', 'lead-form-builder' ); ?>">
                        <?php echo $back_svg; ?>
                    </a>
                <?php } else { ?>
                    <span class="lfb-header-icon"><?php echo $form_svg; ?></span>
                <?php } ?>
                <div class="lfb-header-title-wrap">
                    <h2><?php echo $page_title; ?></h2>
                    <?php if ( $breadcrumb ) { ?>
                        <span class="lfb-breadcrumb"><?php echo esc_html( $breadcrumb ); ?></span>
                    <?php } ?>
                </div>
            </div>
            <div class="lfb-header-right">
                <?php if ( isset( $_GET['page'] ) && $_GET['page'] === 'wplf-plugin-menu' && ! isset( $_GET['action'] ) ) { ?>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=add-new-form&_wpnonce=' . $option ) ); ?>" class="lfb-add-new-btn">
                        <span class="lfb-btn-icon"><?php echo $plus_svg; ?></span>
                        <span><?php esc_html_e( 'Add New Form', 'lead-form-builder' ); ?></span>
                    </a>
                <?php } elseif ( isset( $_GET['action'] ) && $_GET['action'] === 'show' && ! empty( $_fid ) ) { ?>
                    <a href="<?php echo esc_url( admin_url( 'admin.php?page=add-new-form&action=edit&formid=' . $_fid ) ); ?>" class="lfb-add-new-btn lfb-add-new-btn--outline">
                        <span class="lfb-btn-icon"><?php echo lfb_svg( 'edit', 15 ); ?></span>
                        <span><?php esc_html_e( 'Edit Form', 'lead-form-builder' ); ?></span>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>

    <?php
}
