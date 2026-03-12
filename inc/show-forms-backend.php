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
<td class="column-cb">
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
    <button type="button" class="lfb-act-btn lfb-act-btn--settings lfb-act-btn--pro" data-form-id="' . $form_id . '" data-form-title="' . esc_attr( $form_title ) . '" title="' . __( 'Settings (Pro)', 'lead-form-builder' ) . '">' . lfb_svg( 'settings' ) . '<span class="lfb-act-pro-badge">' . lfb_svg( 'lock', 8 ) . '</span></button>
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

        // Pro Settings Modal (rendered once on page)
        $this->lfb_pro_settings_modal();
    }

    function lfb_pro_settings_modal() {
        $upgrade_url = 'https://themehunk.com/lead-form-builder-pro/';
        echo '
<!-- LFB Pro Settings Modal -->
<div class="lfb-pro-modal-overlay" id="lfbProSettingsOverlay" style="display:none;">
  <div class="lfb-pro-modal">

    <div class="lfb-pro-modal-header">
      <div class="lfb-pro-modal-title">
        <span class="lfb-pro-modal-icon">' . lfb_svg( 'settings', 18 ) . '</span>
        <div>
          <h3>' . __( 'Form Settings', 'lead-form-builder' ) . ' &mdash; <span id="lfbProModalFormTitle"></span></h3>
          <p>' . __( 'Available in Premium Version', 'lead-form-builder' ) . '</p>
        </div>
      </div>
      <button type="button" class="lfb-pro-modal-close" id="lfbProModalClose">' . lfb_svg( 'close', 18 ) . '</button>
    </div>

    <div class="lfb-pro-modal-layout">

      <!-- Sidebar tabs (mirrors Pro plugin sidebar) -->
      <nav class="lfb-pro-modal-sidebar">
        <button type="button" class="lfb-pro-stab active" data-tab="lfb-pm-email">
          <span class="lfb-pro-stab-icon">' . lfb_svg( 'mail', 16 ) . '</span>
          <span>' . __( 'Email Notification', 'lead-form-builder' ) . '</span>
          <span class="lfb-pro-stab-lock">' . lfb_svg( 'lock', 10 ) . '</span>
        </button>
        <button type="button" class="lfb-pro-stab" data-tab="lfb-pm-captcha">
          <span class="lfb-pro-stab-icon">' . lfb_svg( 'shield', 16 ) . '</span>
          <span>' . __( 'Spam Protection', 'lead-form-builder' ) . '</span>
          <span class="lfb-pro-stab-lock">' . lfb_svg( 'lock', 10 ) . '</span>
        </button>
        <button type="button" class="lfb-pro-stab" data-tab="lfb-pm-general">
          <span class="lfb-pro-stab-icon">' . lfb_svg( 'settings', 16 ) . '</span>
          <span>' . __( 'Form Settings', 'lead-form-builder' ) . '</span>
          <span class="lfb-pro-stab-lock">' . lfb_svg( 'lock', 10 ) . '</span>
        </button>
        <button type="button" class="lfb-pro-stab" data-tab="lfb-pm-addons">
          <span class="lfb-pro-stab-icon">' . lfb_svg( 'box', 16 ) . '</span>
          <span>' . __( 'Add-Ons', 'lead-form-builder' ) . '</span>
          <span class="lfb-pro-stab-lock">' . lfb_svg( 'lock', 10 ) . '</span>
        </button>
        <button type="button" class="lfb-pro-stab" data-tab="lfb-pm-exports">
          <span class="lfb-pro-stab-icon">' . lfb_svg( 'download', 16 ) . '</span>
          <span>' . __( 'Export &amp; Import', 'lead-form-builder' ) . '</span>
          <span class="lfb-pro-stab-lock">' . lfb_svg( 'lock', 10 ) . '</span>
        </button>
        <button type="button" class="lfb-pro-stab" data-tab="lfb-pm-apikey">
          <span class="lfb-pro-stab-icon">' . lfb_svg( 'key', 16 ) . '</span>
          <span>' . __( 'API Key', 'lead-form-builder' ) . '</span>
          <span class="lfb-pro-stab-lock">' . lfb_svg( 'lock', 10 ) . '</span>
        </button>
        <button type="button" class="lfb-pro-stab" data-tab="lfb-pm-gsignin">
          <span class="lfb-pro-stab-icon">' . lfb_svg( 'user', 16 ) . '</span>
          <span>' . __( 'Google Sign-In', 'lead-form-builder' ) . '</span>
          <span class="lfb-pro-stab-lock">' . lfb_svg( 'lock', 10 ) . '</span>
        </button>
      </nav>

      <!-- Content area -->
      <div class="lfb-pro-modal-body">
        <div class="lfb-pro-lock-overlay">
          <div class="lfb-pro-lock-badge">
            ' . lfb_svg( 'lock', 36 ) . '
            <h4>' . __( 'Premium Feature', 'lead-form-builder' ) . '</h4>
            <p>' . __( 'Unlock all form settings with Lead Form Builder Pro.', 'lead-form-builder' ) . '</p>
            <a href="' . esc_url( $upgrade_url ) . '" target="_blank" class="lfb-pro-upgrade-btn">' . lfb_svg( 'star', 14 ) . ' ' . __( 'Upgrade to Pro', 'lead-form-builder' ) . '</a>
          </div>
        </div>

        <!-- Tab 1: Email Notification -->
        <div class="lfb-pro-tab-content active" id="lfb-pm-email">
          <div class="lfb-pro-panel-title"><h2>' . __( 'Email Notification', 'lead-form-builder' ) . '</h2><p>' . __( 'Configure who receives form submission notifications and what they receive.', 'lead-form-builder' ) . '</p></div>
          <div class="lfb-es-card lfb-pro-card-preview">
            <div class="lfb-es-header">
              <span class="lfb-es-icon lfb-es-icon-admin">' . lfb_svg( 'mail', 22 ) . '</span>
              <div class="lfb-es-header-text"><h3>' . __( 'Admin Email Notifications', 'lead-form-builder' ) . '</h3><p>' . __( 'Configure notification emails sent to admin when a form is submitted.', 'lead-form-builder' ) . '</p></div>
            </div>
            <div class="lfb-es-body">
              <div class="lfb-es-row lfb-es-two-col">
                <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'To', 'lead-form-builder' ) . '</label><input type="email" class="lfb-es-input" value="admin@example.com" disabled></div>
                <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'From', 'lead-form-builder' ) . '</label><input type="email" class="lfb-es-input" value="noreply@example.com" disabled></div>
              </div>
              <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'Multiple Recipients', 'lead-form-builder' ) . '</label><textarea class="lfb-es-input" rows="2" disabled placeholder="a@gmail.com,b@yahoo.com"></textarea></div>
              <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'Email Header', 'lead-form-builder' ) . '</label><input type="text" class="lfb-es-input" value="New Lead Received" disabled></div>
              <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'Subject', 'lead-form-builder' ) . '</label><input type="text" class="lfb-es-input" value="Form Leads" disabled></div>
              <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'Message', 'lead-form-builder' ) . '</label><textarea class="lfb-es-input" rows="3" disabled>[lf-new-form-data]</textarea></div>
            </div>
          </div>
          <div class="lfb-es-card lfb-pro-card-preview" style="margin-top:14px;">
            <div class="lfb-es-header">
              <span class="lfb-es-icon lfb-es-icon-user">' . lfb_svg( 'user', 22 ) . '</span>
              <div class="lfb-es-header-text"><h3>' . __( 'User Email Notifications', 'lead-form-builder' ) . '</h3><p>' . __( 'Send a confirmation email to the user after form submission.', 'lead-form-builder' ) . '</p></div>
            </div>
            <div class="lfb-es-body">
              <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'From', 'lead-form-builder' ) . '</label><input type="email" class="lfb-es-input" value="noreply@example.com" disabled></div>
              <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'Subject', 'lead-form-builder' ) . '</label><input type="text" class="lfb-es-input" value="Received a lead" disabled></div>
              <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'Message', 'lead-form-builder' ) . '</label><textarea class="lfb-es-input" rows="3" disabled>Form Submitted Successfully</textarea></div>
              <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'Send Confirmation Email', 'lead-form-builder' ) . '</label>
                <label class="lfb-es-radio-opt"><input type="radio" disabled> <div class="lfb-es-radio-text"><strong>' . __( 'Send Email', 'lead-form-builder' ) . '</strong></div></label>
                <label class="lfb-es-radio-opt lfb-es-radio-checked"><input type="radio" disabled checked> <div class="lfb-es-radio-text"><strong>' . __( "Don't Send", 'lead-form-builder' ) . '</strong></div></label>
              </div>
            </div>
          </div>
        </div>

        <!-- Tab 2: Spam Protection -->
        <div class="lfb-pro-tab-content" id="lfb-pm-captcha">
          <div class="lfb-pro-panel-title"><h2>' . __( 'Spam Protection', 'lead-form-builder' ) . '</h2><p>' . __( 'Configure Google reCAPTCHA to prevent spam submissions on your form.', 'lead-form-builder' ) . '</p></div>
          <div class="lfb-setting-two-col">
            <div class="lfb-es-card lfb-pro-card-preview">
              <div class="lfb-es-header">
                <span class="lfb-es-icon lfb-es-icon-captcha">' . lfb_svg( 'lock', 22 ) . '</span>
                <div class="lfb-es-header-text"><h3>' . __( 'reCAPTCHA Setup', 'lead-form-builder' ) . '</h3><p>' . __( 'Protect your forms from spam and abuse.', 'lead-form-builder' ) . '</p></div>
              </div>
              <div class="lfb-es-body">
                <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'Captcha Version', 'lead-form-builder' ) . '</label><input type="text" class="lfb-es-input" value="reCAPTCHA v2 (Checkbox)" disabled></div>
                <div class="lfb-es-row lfb-es-two-col">
                  <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'Site Key', 'lead-form-builder' ) . '</label><input type="text" class="lfb-es-input" value="••••••••••••••••" disabled></div>
                  <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'Secret Key', 'lead-form-builder' ) . '</label><input type="text" class="lfb-es-input" value="••••••••••••••••" disabled></div>
                </div>
              </div>
            </div>
            <div class="lfb-es-card lfb-pro-card-preview">
              <div class="lfb-es-header">
                <span class="lfb-es-icon lfb-es-icon-shield">' . lfb_svg( 'shield', 22 ) . '</span>
                <div class="lfb-es-header-text"><h3>' . __( 'Captcha On / Off', 'lead-form-builder' ) . '</h3><p>' . __( 'Enable or disable reCAPTCHA for this specific form.', 'lead-form-builder' ) . '</p></div>
              </div>
              <div class="lfb-es-body">
                <div class="lfb-es-field">
                  <label class="lfb-es-radio-opt"><input type="radio" disabled> <div class="lfb-es-radio-text"><strong>' . __( 'Enable Captcha', 'lead-form-builder' ) . '</strong></div></label>
                  <label class="lfb-es-radio-opt lfb-es-radio-checked"><input type="radio" disabled checked> <div class="lfb-es-radio-text"><strong>' . __( 'Disable Captcha', 'lead-form-builder' ) . '</strong></div></label>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tab 3: Form Settings -->
        <div class="lfb-pro-tab-content" id="lfb-pm-general">
          <div class="lfb-pro-panel-title"><h2>' . __( 'Form Settings', 'lead-form-builder' ) . '</h2><p>' . __( 'Configure form redirection, lead storage method, and thank-you message.', 'lead-form-builder' ) . '</p></div>
          <div class="lfb-setting-two-col">
            <div class="lfb-es-card lfb-pro-card-preview">
              <div class="lfb-es-header">
                <span class="lfb-es-icon lfb-es-icon-lead">' . lfb_svg( 'download', 22 ) . '</span>
                <div class="lfb-es-header-text"><h3>' . __( 'Lead Receiving Method', 'lead-form-builder' ) . '</h3><p>' . __( 'Choose how submitted leads are handled for this form.', 'lead-form-builder' ) . '</p></div>
              </div>
              <div class="lfb-es-body">
                <div class="lfb-es-field">
                  <label class="lfb-es-radio-opt"><input type="radio" disabled> <div class="lfb-es-radio-text"><strong>' . __( 'Receive Leads in Email', 'lead-form-builder' ) . '</strong><span>' . __( 'Leads are sent directly to the configured admin email.', 'lead-form-builder' ) . '</span></div></label>
                  <label class="lfb-es-radio-opt lfb-es-radio-checked"><input type="radio" disabled checked> <div class="lfb-es-radio-text"><strong>' . __( 'Save Leads in Database', 'lead-form-builder' ) . '</strong><span>' . __( 'All leads stored in database and visible in leads list.', 'lead-form-builder' ) . '</span></div></label>
                  <label class="lfb-es-radio-opt"><input type="radio" disabled> <div class="lfb-es-radio-text"><strong>' . __( 'Email + Save in Database', 'lead-form-builder' ) . '</strong><span>' . __( 'Receive leads by email and also save them in the database.', 'lead-form-builder' ) . '</span></div></label>
                </div>
              </div>
            </div>
            <div class="lfb-es-card lfb-pro-card-preview">
              <div class="lfb-es-header">
                <span class="lfb-es-icon lfb-es-icon-thankyou">' . lfb_svg( 'check-circle', 22 ) . '</span>
                <div class="lfb-es-header-text"><h3>' . __( 'Thank You Message', 'lead-form-builder' ) . '</h3><p>' . __( 'Displayed to the visitor after the form is submitted successfully.', 'lead-form-builder' ) . '</p></div>
              </div>
              <div class="lfb-es-body">
                <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'Success Message', 'lead-form-builder' ) . '</label><textarea class="lfb-es-input" rows="3" disabled>' . __( 'Thank you for your valuable feedback.', 'lead-form-builder' ) . '</textarea></div>
                <div class="lfb-es-field lfb-pro-field">
                  <div class="lfb-pro-badge">' . lfb_svg( 'lock', 10 ) . ' ' . __( 'Pro', 'lead-form-builder' ) . '</div>
                  <label class="lfb-es-label">' . __( 'Redirect URL', 'lead-form-builder' ) . '</label>
                  <input type="url" class="lfb-es-input" placeholder="https://example.com/thank-you" disabled>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tab 4: Add-Ons -->
        <div class="lfb-pro-tab-content" id="lfb-pm-addons">
          <div class="lfb-pro-panel-title"><h2>' . __( 'Add-Ons', 'lead-form-builder' ) . '</h2><p>' . __( 'Connect third-party services and configure integrations for this form.', 'lead-form-builder' ) . '</p></div>
          <div class="lfb-pro-addon-grid">
            <div class="lfb-pro-addon-card">
              <div class="lfb-pro-addon-icon lfb-pro-addon-icon--amber">' . lfb_svg( 'mail', 22 ) . '</div>
              <div class="lfb-pro-addon-body">
                <strong>' . __( 'MailChimp', 'lead-form-builder' ) . '</strong>
                <span>' . __( 'Connect your MailChimp account and sync new leads to your audience lists.', 'lead-form-builder' ) . '</span>
              </div>
              <span class="lfb-pro-addon-lock">' . lfb_svg( 'lock', 14 ) . '</span>
            </div>
            <div class="lfb-pro-addon-card">
              <div class="lfb-pro-addon-icon lfb-pro-addon-icon--blue">' . lfb_svg( 'monitor', 22 ) . '</div>
              <div class="lfb-pro-addon-body">
                <strong>' . __( 'SMTP Mail Configure', 'lead-form-builder' ) . '</strong>
                <span>' . __( 'Set up a global SMTP server for sending emails from all your forms.', 'lead-form-builder' ) . '</span>
              </div>
              <span class="lfb-pro-addon-lock">' . lfb_svg( 'lock', 14 ) . '</span>
            </div>
          </div>
          <div class="lfb-es-card lfb-pro-card-preview" style="margin-top:14px;">
            <div class="lfb-es-header">
              <span class="lfb-es-icon lfb-es-icon-admin">' . lfb_svg( 'mail', 22 ) . '</span>
              <div class="lfb-es-header-text"><h3>' . __( 'MailChimp API Key', 'lead-form-builder' ) . '</h3><p>' . __( 'Enter your MailChimp API key to connect your account.', 'lead-form-builder' ) . '</p></div>
            </div>
            <div class="lfb-es-body">
              <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'API Key', 'lead-form-builder' ) . '</label><input type="text" class="lfb-es-input" value="••••••••••••••••-us1" disabled><span class="lfb-es-hint">' . __( 'Find your API key in MailChimp → Account → Extras → API keys.', 'lead-form-builder' ) . '</span></div>
            </div>
          </div>
          <div class="lfb-es-card lfb-pro-card-preview" style="margin-top:14px;">
            <div class="lfb-es-header">
              <span class="lfb-es-icon lfb-es-icon-captcha">' . lfb_svg( 'monitor', 22 ) . '</span>
              <div class="lfb-es-header-text"><h3>' . __( 'SMTP Configuration', 'lead-form-builder' ) . '</h3><p>' . __( 'Configure your SMTP server to send form emails reliably.', 'lead-form-builder' ) . '</p></div>
            </div>
            <div class="lfb-es-body">
              <div class="lfb-es-row lfb-es-two-col">
                <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'SMTP Name', 'lead-form-builder' ) . '</label><input type="text" class="lfb-es-input" value="Gmail" disabled></div>
                <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'SMTP Server', 'lead-form-builder' ) . '</label><input type="text" class="lfb-es-input" value="smtp.gmail.com" disabled></div>
              </div>
              <div class="lfb-es-row lfb-es-two-col">
                <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'Port', 'lead-form-builder' ) . '</label><input type="text" class="lfb-es-input" value="587" disabled></div>
                <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'Encryption', 'lead-form-builder' ) . '</label><input type="text" class="lfb-es-input" value="TLS" disabled></div>
              </div>
              <div class="lfb-es-row lfb-es-two-col">
                <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'Username', 'lead-form-builder' ) . '</label><input type="text" class="lfb-es-input" value="you@gmail.com" disabled></div>
                <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'Password', 'lead-form-builder' ) . '</label><input type="password" class="lfb-es-input" value="••••••••••" disabled></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tab 5: Export & Import -->
        <div class="lfb-pro-tab-content" id="lfb-pm-exports">
          <div class="lfb-pro-panel-title"><h2>' . __( 'Export &amp; Import', 'lead-form-builder' ) . '</h2><p>' . __( 'Export leads as CSV or backup and restore form configurations.', 'lead-form-builder' ) . '</p></div>
          <div class="lfb-setting-two-col">
            <div class="lfb-es-card lfb-pro-card-preview">
              <div class="lfb-es-header">
                <span class="lfb-es-icon lfb-es-icon-lead">' . lfb_svg( 'download', 22 ) . '</span>
                <div class="lfb-es-header-text"><h3>' . __( 'Export Leads to CSV', 'lead-form-builder' ) . '</h3><p>' . __( 'Select a date range and download all form submissions as a CSV file.', 'lead-form-builder' ) . '</p></div>
              </div>
              <div class="lfb-es-body">
                <div class="lfb-es-row lfb-es-two-col">
                  <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'Start Date', 'lead-form-builder' ) . '</label><input type="text" class="lfb-es-input" value="01/01/2025" disabled></div>
                  <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'End Date', 'lead-form-builder' ) . '</label><input type="text" class="lfb-es-input" value="12/31/2025" disabled></div>
                </div>
              </div>
            </div>
            <div class="lfb-es-card lfb-pro-card-preview">
              <div class="lfb-es-header">
                <span class="lfb-es-icon lfb-es-icon-shield">' . lfb_svg( 'file', 22 ) . '</span>
                <div class="lfb-es-header-text"><h3>' . __( 'Form Export / Import', 'lead-form-builder' ) . '</h3><p>' . __( 'Backup, restore or migrate forms using JSON-based import and export.', 'lead-form-builder' ) . '</p></div>
              </div>
              <div class="lfb-es-body">
                <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'Export Form', 'lead-form-builder' ) . '</label><input type="text" class="lfb-es-input" value="form-backup.json" disabled><span class="lfb-es-hint">' . __( 'Downloads the form structure as a .json file.', 'lead-form-builder' ) . '</span></div>
                <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'Import Form (JSON)', 'lead-form-builder' ) . '</label><input type="text" class="lfb-es-input" value="Choose JSON file..." disabled><span class="lfb-es-hint">' . __( 'Use the Import option to restore it on any site.', 'lead-form-builder' ) . '</span></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tab 6: API Key -->
        <div class="lfb-pro-tab-content" id="lfb-pm-apikey">
          <div class="lfb-pro-panel-title"><h2>' . __( 'API Key', 'lead-form-builder' ) . '</h2><p>' . __( 'Manage REST API keys to integrate this form with external applications.', 'lead-form-builder' ) . '</p></div>
          <div class="lfb-es-card lfb-pro-card-preview">
            <div class="lfb-es-header">
              <span class="lfb-es-icon lfb-es-icon-captcha">' . lfb_svg( 'database', 22 ) . '</span>
              <div class="lfb-es-header-text"><h3>' . __( 'REST API Access Key', 'lead-form-builder' ) . '</h3><p>' . __( 'Use this key to authenticate API requests for this form.', 'lead-form-builder' ) . '</p></div>
            </div>
            <div class="lfb-es-body">
              <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'API Key', 'lead-form-builder' ) . '</label><input type="text" class="lfb-es-input" value="lfb_api_••••••••••••••••••••" disabled><span class="lfb-es-hint">' . __( 'Keep this key secret. Regenerate it if compromised.', 'lead-form-builder' ) . '</span></div>
              <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'API Endpoint', 'lead-form-builder' ) . '</label><input type="text" class="lfb-es-input" value="/wp-json/lfb/v1/leads/{form_id}" disabled></div>
            </div>
          </div>
        </div>

        <!-- Tab 7: Google Sign-In -->
        <div class="lfb-pro-tab-content" id="lfb-pm-gsignin">
          <div class="lfb-pro-panel-title"><h2>' . __( 'Google Sign-In', 'lead-form-builder' ) . '</h2><p>' . __( 'Allow users to auto-fill form fields using their Google account.', 'lead-form-builder' ) . '</p></div>
          <div class="lfb-es-card lfb-pro-card-preview">
            <div class="lfb-es-header">
              <span class="lfb-es-icon lfb-es-icon-user">' . lfb_svg( 'user', 22 ) . '</span>
              <div class="lfb-es-header-text"><h3>' . __( 'Google OAuth Configuration', 'lead-form-builder' ) . '</h3><p>' . __( 'Configure Google Sign-In to allow users to auto-fill form fields from their Google profile.', 'lead-form-builder' ) . '</p></div>
            </div>
            <div class="lfb-es-body">
              <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'Google Client ID', 'lead-form-builder' ) . '</label><input type="text" class="lfb-es-input" value="••••••••••••.apps.googleusercontent.com" disabled><span class="lfb-es-hint">' . __( 'Get this from Google Cloud Console → Credentials.', 'lead-form-builder' ) . '</span></div>
              <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'Redirect / Login URI', 'lead-form-builder' ) . '</label><input type="text" class="lfb-es-input" value="https://example.com/wp-admin/" disabled></div>
              <div class="lfb-es-field"><label class="lfb-es-label">' . __( 'Field Mapping', 'lead-form-builder' ) . '</label>
                <div class="lfb-pro-field-map-preview">
                  <div class="lfb-pro-fmp-row"><span>' . __( 'Name field', 'lead-form-builder' ) . '</span><span>→</span><span>' . __( 'Google: name', 'lead-form-builder' ) . '</span></div>
                  <div class="lfb-pro-fmp-row"><span>' . __( 'Email field', 'lead-form-builder' ) . '</span><span>→</span><span>' . __( 'Google: email', 'lead-form-builder' ) . '</span></div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div><!-- /.lfb-pro-modal-body -->
    </div><!-- /.lfb-pro-modal-layout -->

    <div class="lfb-pro-modal-footer">
      <p>' . __( 'Get all settings + advanced features with', 'lead-form-builder' ) . ' <strong>Lead Form Builder Pro</strong></p>
      <a href="' . esc_url( $upgrade_url ) . '" target="_blank" class="lfb-pro-upgrade-btn">' . lfb_svg( 'star', 14 ) . ' ' . __( 'Upgrade to Pro', 'lead-form-builder' ) . '</a>
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
<td class="column-cb">
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
    <button type="button" class="lfb-act-btn lfb-act-btn--settings lfb-act-btn--pro" data-form-id="' . $form_id . '" data-form-title="' . esc_attr( $form_title ) . '" title="' . __( 'Settings (Pro)', 'lead-form-builder' ) . '">' . lfb_svg( 'settings' ) . '<span class="lfb-act-pro-badge">' . lfb_svg( 'lock', 8 ) . '</span></button>
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
