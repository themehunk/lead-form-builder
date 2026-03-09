<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

Class LFB_SHOW_FORMS {

    function lfb_show_form_nonce() {
        return wp_create_nonce( '_nonce_verify' );
    }

    function lfb_show_all_forms( $id ) {
        $lfb_admin_url = admin_url();
        echo '<div class="wrap show-all-form">';
        lfb_admin_menu_header( 'show-form-backend', $this->lfb_show_form_nonce() );

        echo '<div class="lfb-main-tabs-wrapper">
<ul class="lfb-main-tabs">
<li class="lfb-main-tab active" data-tab="lfb-tab-formlist">' . __( 'Form List', 'lead-form-builder' ) . '</li>
<li class="lfb-main-tab" data-tab="lfb-tab-viewleads" data-href="' . esc_url( admin_url( 'admin.php?page=all-form-leads' ) ) . '">' . __( 'View Leads', 'lead-form-builder' ) . '</li>
<li class="lfb-main-tab" data-tab="lfb-tab-upgrade" data-href="' . esc_url( admin_url( 'admin.php?page=pro-form-leads' ) ) . '">' . __( 'Upgrade to Pro', 'lead-form-builder' ) . '</li>
</ul>

<div class="lfb-main-tab-content active" id="lfb-tab-formlist">
<div>
<div class="lfb-bulk-actions-bar">
    <div class="lfb-bulk-left">
        <span class="lfb-bulk-icon">' . lfb_svg( 'select-all' ) . '</span>
        <span class="lfb-selected-count"><b class="lfb-sel-num">0</b> ' . __( 'forms selected', 'lead-form-builder' ) . '</span>
    </div>
    <div class="lfb-bulk-right">
        <button type="button" class="lfb-bulk-delete-btn">' . lfb_svg( 'trash' ) . ' ' . __( 'Delete', 'lead-form-builder' ) . '</button>
        <button type="button" class="lfb-bulk-cancel-btn">' . lfb_svg( 'close', 14 ) . ' ' . __( 'Cancel', 'lead-form-builder' ) . '</button>
    </div>
</div>

<div class="lfb-delete-modal-overlay" style="display:none;">
    <div class="lfb-delete-modal">
        <div class="lfb-modal-icon">' . lfb_svg( 'warning' ) . '</div>
        <h3>' . __( 'Delete Forms?', 'lead-form-builder' ) . '</h3>
        <p class="lfb-modal-msg">' . __( 'This will permanently remove the selected forms. This action cannot be undone.', 'lead-form-builder' ) . '</p>
        <div class="lfb-modal-actions">
            <button type="button" class="lfb-modal-cancel-btn">' . __( 'Cancel', 'lead-form-builder' ) . '</button>
            <button type="button" class="lfb-modal-confirm-btn">' . lfb_svg( 'trash' ) . ' ' . __( 'Yes, Delete', 'lead-form-builder' ) . '</button>
        </div>
    </div>
</div>

<table class="wp-list-table widefat fixed striped posts lfb-form-table">
    <thead>
    <tr>
        <th scope="col" class="manage-column column-cb lfb-col-cb"><label class="lfb-custom-cb"><input type="checkbox" class="lfb-select-all" /><span class="lfb-cb-mark"></span></label></th>
        <th scope="col" class="manage-column lfb-th-title">' . __( 'Title', 'lead-form-builder' ) . '</th>
        <th scope="col" class="manage-column lfb-th-shortcode">' . __( 'Shortcode', 'lead-form-builder' ) . '</th>
        <th scope="col" class="manage-column lfb-th-count">' . __( "Today's Lead", 'lead-form-builder' ) . '</th>
        <th scope="col" class="manage-column lfb-th-count">' . __( 'Total Lead', 'lead-form-builder' ) . '</th>
        <th scope="col" class="manage-column lfb-th-date">' . __( 'Date', 'lead-form-builder' ) . '</th>
        <th scope="col" class="manage-column lfb-th-actions">' . __( 'Actions', 'lead-form-builder' ) . '</th>
    </tr>
    </thead>
    <tbody id="the-list" data-wp-lists="list:post">';

        global $wpdb;
        $th_save_db = new LFB_SAVE_DB( $wpdb );
        $table_name = LFB_FORM_FIELD_TBL;
        $limit      = 10;
        $start      = ( $id - 1 ) * $limit;
        $prepare    = $wpdb->prepare( "SELECT * FROM $table_name WHERE form_status = %s ORDER BY id DESC LIMIT $start, $limit", 'ACTIVE' );
        $posts      = $th_save_db->lfb_get_form_content( $prepare );

        if ( $posts ) {
            $th_save_db = new LFB_SAVE_DB();
            foreach ( $posts as $results ) {
                $form_title        = $results->form_title;
                $form_date         = $results->date;
                $form_id           = $results->id;
                $lead_count        = $th_save_db->today_lead_count( $form_id );
                $total_lead_result = $th_save_db->total_leads_count( $form_id );
                $edit_url_nonce    = $lfb_admin_url . 'admin.php?page=add-new-form&action=edit&formid=' . $form_id . '&_wpnonce=' . $this->lfb_show_form_nonce();
                $form_preview_url  = $lfb_admin_url . 'admin.php?page=wplf-plugin-menu&action=show&hide_elementor_msg=1&formid=' . $form_id;
                $delete_url        = $lfb_admin_url . 'admin.php?page=wplf-plugin-menu&action=delete&page_id=' . $id . '&formid=' . $form_id . '&_wpnonce=' . $this->lfb_show_form_nonce();
                $sc_full           = '[lead-form form-id=' . $form_id . ' title=' . esc_attr( $form_title ) . ']';
                $sc_short          = '[lead-form id=' . $form_id . ']';

                echo '
<tr data-form-id="' . $form_id . '">
<td class="lfb-col-cb column-cb">
  <label class="lfb-custom-cb"><input type="checkbox" class="lfb-form-cb" value="' . $form_id . '" /><span class="lfb-cb-mark"></span></label>
</td>
<td class="lfb-col-title" data-colname="Title">
  <a class="lfb-form-title-link" href="' . esc_url( $edit_url_nonce ) . '">' . esc_html( $form_title ) . '</a>
  <button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
</td>
<td class="lfb-col-shortcode" data-colname="Shortcode">
  <span class="lfb-sc-pill">
    <span class="lfb-sc-label">' . esc_html( $sc_short ) . '</span>
    <input type="text" onfocus="this.select();" value="' . esc_attr( $sc_full ) . '" class="large-text code lfb-sc-input" name="copycode" readonly>
    <a class="lfb-copy lfb-sc-copy" title="' . __( 'Copy', 'lead-form-builder' ) . '">' . lfb_svg( 'duplicate', 12 ) . '</a>
  </span>
</td>
<td class="lfb-col-count" data-colname="Today">
  <a href="' . esc_url( $lfb_admin_url . 'admin.php?page=wplf-plugin-menu&action=today_leads&formid=' . $form_id ) . '" class="lfb-lead-num">' . intval( $lead_count ) . '</a>
</td>
<td class="lfb-col-count" data-colname="Total">
  <a href="' . esc_url( $lfb_admin_url . 'admin.php?page=wplf-plugin-menu&action=total_leads&formid=' . $form_id ) . '" class="lfb-lead-num">' . intval( $total_lead_result ) . '</a>
</td>
<td class="lfb-col-date" data-colname="Date">' . esc_html( date( 'd M, Y', strtotime( $form_date ) ) ) . '</td>
<td class="lfb-col-actions" data-colname="Actions">
  <div class="lfb-row-act">
    <a href="' . esc_url( $edit_url_nonce ) . '" class="lfb-act-btn" title="' . __( 'Edit', 'lead-form-builder' ) . '">' . lfb_svg( 'edit' ) . '</a>
    <a href="' . esc_url( $form_preview_url ) . '" class="lfb-act-btn" title="' . __( 'View', 'lead-form-builder' ) . '" target="_blank">' . lfb_svg( 'eye' ) . '</a>
    <button type="button" class="lfb-act-btn lfb-act-btn--dup" data-form-id="' . $form_id . '" title="' . __( 'Duplicate', 'lead-form-builder' ) . '">' . lfb_svg( 'duplicate' ) . '</button>
    <a href="' . esc_url( $delete_url ) . '" class="lfb-act-btn lfb-act-btn--danger" title="' . __( 'Delete', 'lead-form-builder' ) . '">' . lfb_svg( 'trash' ) . '</a>
  </div>
</td>
</tr>';
            }
        }

        echo '</tbody>
  </table>';
        echo '<div class="tablenav bottom" id="lfb-forms-pagi-wrap">';
        echo $this->lfb_form_pagi_html( $id, $this->lfb_form_total_pages() );
        echo '</div></div>
</div>
</div>
</div>';
    }

    function lfb_form_total_pages( $limit = 10 ) {
        global $wpdb;
        $table_name = LFB_FORM_FIELD_TBL;
        $count      = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM $table_name WHERE form_status = %s", 'ACTIVE' ) );
        return (int) ceil( $count / $limit );
    }

    function lfb_form_pagi_html( $current, $total_pages ) {
        if ( $total_pages <= 1 ) return '';
        $prev_svg = lfb_svg( 'chevron-left' );
        $next_svg = lfb_svg( 'chevron-right' );
        $html     = '<div class="lfb-pagination">';
        if ( $current <= 1 ) {
            $html .= '<button class="lfb-pagi-btn lfb-pagi-nav lfb-pagi-disabled" disabled>' . $prev_svg . '</button>';
        } else {
            $html .= '<button class="lfb-pagi-btn lfb-pagi-nav" onclick="lfbFormPage(' . ( $current - 1 ) . ')">' . $prev_svg . '</button>';
        }
        for ( $i = 1; $i <= $total_pages; $i++ ) {
            $active = ( $i == $current ) ? ' lfb-pagi-active' : '';
            $html  .= '<button class="lfb-pagi-btn' . $active . '" onclick="lfbFormPage(' . $i . ')">' . $i . '</button>';
        }
        if ( $current >= $total_pages ) {
            $html .= '<button class="lfb-pagi-btn lfb-pagi-nav lfb-pagi-disabled" disabled>' . $next_svg . '</button>';
        } else {
            $html .= '<button class="lfb-pagi-btn lfb-pagi-nav" onclick="lfbFormPage(' . ( $current + 1 ) . ')">' . $next_svg . '</button>';
        }
        $html .= '</div>';
        return $html;
    }

    function lfb_render_form_rows( $id ) {
        global $wpdb;
        $lfb_admin_url = admin_url();
        $th_save_db    = new LFB_SAVE_DB( $wpdb );
        $table_name    = LFB_FORM_FIELD_TBL;
        $limit         = 10;
        $start         = ( $id - 1 ) * $limit;
        $prepare       = $wpdb->prepare( "SELECT * FROM $table_name WHERE form_status = %s ORDER BY id DESC LIMIT $start, $limit", 'ACTIVE' );
        $posts         = $th_save_db->lfb_get_form_content( $prepare );
        ob_start();
        if ( $posts ) {
            $th_save_db = new LFB_SAVE_DB();
            foreach ( $posts as $results ) {
                $form_title        = $results->form_title;
                $form_date         = $results->date;
                $form_id           = $results->id;
                $lead_count        = $th_save_db->today_lead_count( $form_id );
                $total_lead_result = $th_save_db->total_leads_count( $form_id );
                $edit_url_nonce    = $lfb_admin_url . 'admin.php?page=add-new-form&action=edit&formid=' . $form_id . '&_wpnonce=' . $this->lfb_show_form_nonce();
                $form_preview_url  = $lfb_admin_url . 'admin.php?page=wplf-plugin-menu&action=show&hide_elementor_msg=1&formid=' . $form_id;
                $delete_url        = $lfb_admin_url . 'admin.php?page=wplf-plugin-menu&action=delete&page_id=' . $id . '&formid=' . $form_id . '&_wpnonce=' . $this->lfb_show_form_nonce();
                $sc_full           = '[lead-form form-id=' . $form_id . ' title=' . esc_attr( $form_title ) . ']';
                $sc_short          = '[lead-form id=' . $form_id . ']';

                echo '
<tr data-form-id="' . $form_id . '">
<td class="lfb-col-cb column-cb">
  <label class="lfb-custom-cb"><input type="checkbox" class="lfb-form-cb" value="' . $form_id . '" /><span class="lfb-cb-mark"></span></label>
</td>
<td class="lfb-col-title" data-colname="Title">
  <a class="lfb-form-title-link" href="' . esc_url( $edit_url_nonce ) . '">' . esc_html( $form_title ) . '</a>
  <button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
</td>
<td class="lfb-col-shortcode" data-colname="Shortcode">
  <span class="lfb-sc-pill">
    <span class="lfb-sc-label">' . esc_html( $sc_short ) . '</span>
    <input type="text" onfocus="this.select();" value="' . esc_attr( $sc_full ) . '" class="large-text code lfb-sc-input" name="copycode" readonly>
    <a class="lfb-copy lfb-sc-copy" title="' . __( 'Copy', 'lead-form-builder' ) . '">' . lfb_svg( 'duplicate', 12 ) . '</a>
  </span>
</td>
<td class="lfb-col-count" data-colname="Today">
  <a href="' . esc_url( $lfb_admin_url . 'admin.php?page=wplf-plugin-menu&action=today_leads&formid=' . $form_id ) . '" class="lfb-lead-num">' . intval( $lead_count ) . '</a>
</td>
<td class="lfb-col-count" data-colname="Total">
  <a href="' . esc_url( $lfb_admin_url . 'admin.php?page=wplf-plugin-menu&action=total_leads&formid=' . $form_id ) . '" class="lfb-lead-num">' . intval( $total_lead_result ) . '</a>
</td>
<td class="lfb-col-date" data-colname="Date">' . esc_html( date( 'd M, Y', strtotime( $form_date ) ) ) . '</td>
<td class="lfb-col-actions" data-colname="Actions">
  <div class="lfb-row-act">
    <a href="' . esc_url( $edit_url_nonce ) . '" class="lfb-act-btn" title="' . __( 'Edit', 'lead-form-builder' ) . '">' . lfb_svg( 'edit' ) . '</a>
    <a href="' . esc_url( $form_preview_url ) . '" class="lfb-act-btn" title="' . __( 'View', 'lead-form-builder' ) . '" target="_blank">' . lfb_svg( 'eye' ) . '</a>
    <button type="button" class="lfb-act-btn lfb-act-btn--dup" data-form-id="' . $form_id . '" title="' . __( 'Duplicate', 'lead-form-builder' ) . '">' . lfb_svg( 'duplicate' ) . '</button>
    <a href="' . esc_url( $delete_url ) . '" class="lfb-act-btn lfb-act-btn--danger" title="' . __( 'Delete', 'lead-form-builder' ) . '">' . lfb_svg( 'trash' ) . '</a>
  </div>
</td>
</tr>';
            }
        }
        return ob_get_clean();
    }
}
