<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
class LFB_EmailSettingForm
{
    function __construct($this_form_id)
    {
        global $wpdb;
        $th_save_db = new LFB_SAVE_DB($wpdb);
        $table_name = LFB_FORM_FIELD_TBL;
        $prepare_9 =  $wpdb->prepare("SELECT * FROM $table_name WHERE id = %d LIMIT 1", $this_form_id);
        $posts = $th_save_db->lfb_get_form_content($prepare_9);
        if ($posts) {
            $form_title = $posts[0]->form_title;
            $form_status = $posts[0]->form_status;
            $captcha_status = $posts[0]->captcha_status;
            $storeType = $posts[0]->storeType;
            $storedate = $posts[0]->date;
            $mail_setting = maybe_unserialize($posts[0]->mail_setting);
            $usermail_setting = maybe_unserialize($posts[0]->usermail_setting);
            $form_data = maybe_unserialize($posts[0]->form_data);
        }
    }

    function lfb_email_setting_form($this_form_id, $mail_setting_result, $usermail_setting)
    {
        $mail_setting_to      = get_option('admin_email');
        $mail_setting_from    = get_option('admin_email');
        $mail_setting_subject = esc_html__( 'Form Leads', 'lead-form-builder' );
        $mail_setting_message = '[lf-new-form-data]';
        $multi_mail           = '';
        $mail_setting_header  = esc_html__( 'New Lead Received', 'lead-form-builder' );
        if ( ! empty( $mail_setting_result ) ) {
            $mail_setting_result  = maybe_unserialize( $mail_setting_result );
            $mail_setting_to      = $mail_setting_result['email_setting']['to'];
            $mail_setting_from    = $mail_setting_result['email_setting']['from'];
            $mail_setting_subject = $mail_setting_result['email_setting']['subject'];
            $mail_setting_message = $mail_setting_result['email_setting']['message'];
            $multi_mail           = isset( $mail_setting_result['email_setting']['multiple'] ) ? $mail_setting_result['email_setting']['multiple'] : '';
            $mail_setting_header  = isset( $mail_setting_result['email_setting']['header'] ) ? $mail_setting_result['email_setting']['header'] : $mail_setting_header;
        }
        $aes_nonce = wp_create_nonce( 'aes-nonce' );

        echo "<form id='form-email-setting' action='' method='post'>
<div class='lfb-es-card'>
  <div class='lfb-es-header'>
    <span class='lfb-es-icon lfb-es-icon-admin'>" . lfb_svg( 'mail', 22 ) . "</span>
    <div class='lfb-es-header-text'>
      <h3>" . esc_html__( 'Admin Email Notifications', 'lead-form-builder' ) . "</h3>
      <p>" . esc_html__( 'Configure notification emails sent to admin when a form is submitted.', 'lead-form-builder' ) . "</p>
    </div>
  </div>
  <div class='lfb-es-body'>
    <div class='lfb-es-row lfb-es-two-col'>
      <div class='lfb-es-field'>
        <label class='lfb-es-label'>" . esc_html__( 'To', 'lead-form-builder' ) . " <span class='lfb-req'>*</span></label>
        <input name='email_setting[to]' required type='email' id='email_setting_to' value='" . esc_attr( $mail_setting_to ) . "' class='lfb-es-input'>
        <span class='lfb-es-hint'>" . esc_html__( 'Admin email address to receive notifications.', 'lead-form-builder' ) . "</span>
      </div>
      <div class='lfb-es-field'>
        <label class='lfb-es-label'>" . esc_html__( 'From', 'lead-form-builder' ) . " <span class='lfb-req'>*</span></label>
        <input name='email_setting[from]' required type='email' id='email_setting_from' value='" . esc_attr( $mail_setting_from ) . "' class='lfb-es-input'>
        <span class='lfb-es-hint'>" . esc_html__( 'Sender email address.', 'lead-form-builder' ) . "</span>
      </div>
    </div>
    <div class='lfb-es-field'>
      <label class='lfb-es-label'>" . esc_html__( 'Multiple Recipients', 'lead-form-builder' ) . "</label>
      <textarea name='email_setting[multiple]' id='email_setting_multiple' rows='3' class='lfb-es-input'>" . esc_textarea( $multi_mail ) . "</textarea>
      <span class='lfb-es-hint'>" . esc_html__( 'Comma-separated:', 'lead-form-builder' ) . " <code>a@gmail.com,b@yahoo.com</code></span>
    </div>
    <div class='lfb-es-field'>
      <label class='lfb-es-label'>" . esc_html__( 'Email Header', 'lead-form-builder' ) . " <span class='lfb-req'>*</span></label>
      <input name='email_setting[header]' required type='text' id='email_setting_header' value='" . esc_attr( $mail_setting_header ) . "' class='lfb-es-input'>
      <span class='lfb-es-hint'>" . esc_html__( 'Headline shown at the top of the email.', 'lead-form-builder' ) . "</span>
    </div>
    <div class='lfb-es-field'>
      <label class='lfb-es-label'>" . esc_html__( 'Subject', 'lead-form-builder' ) . " <span class='lfb-req'>*</span></label>
      <input name='email_setting[subject]' required type='text' id='email_setting_subject' value='" . esc_attr( $mail_setting_subject ) . "' class='lfb-es-input'>
      <span class='lfb-es-hint'>" . esc_html__( 'Use', 'lead-form-builder' ) . " <code>[name]</code> " . esc_html__( 'for the user\'s name.', 'lead-form-builder' ) . "</span>
    </div>
    <div class='lfb-es-field'>
      <label class='lfb-es-label'>" . esc_html__( 'Message', 'lead-form-builder' ) . " <span class='lfb-req'>*</span></label>
      <textarea name='email_setting[message]' id='email_setting_message' rows='5' required class='lfb-es-input'>" . esc_textarea( $mail_setting_message ) . "</textarea>
      <span class='lfb-es-hint'><code>[lf-new-form-data]</code> &mdash; " . esc_html__( 'All form entries', 'lead-form-builder' ) . " &nbsp;|&nbsp; <code>[name]</code> &mdash; " . esc_html__( 'User name in subject', 'lead-form-builder' ) . "</span>
    </div>
    <div class='lfb-es-footer'>
      <input type='hidden' name='email_setting[form-id]' value='" . intval( $this_form_id ) . "'>
      <input type='hidden' name='aes_nonce' value='" . esc_attr( $aes_nonce ) . "'>
      <div id='error-message-email-setting'></div>
      <button type='submit' class='lfb-es-save-btn'><i class='fa fa-check'></i> " . esc_html__( 'Save Settings', 'lead-form-builder' ) . "</button>
    </div>
  </div>
</div>
</form>";

        $usermail_setting_from    = get_option('admin_email');
        $usermail_setting_subject = esc_html( 'Received a lead' );
        $usermail_setting_message = esc_html( 'Form Submitted Successfully' );
        $usermail_setting_option  = 'OFF';
        $usermail_setting_header  = esc_html( 'New Lead Received' );
        if ( ! empty( $usermail_setting ) ) {
            $usermail_setting_result  = maybe_unserialize( $usermail_setting );
            $usermail_setting_from    = $usermail_setting_result['user_email_setting']['from'];
            $usermail_setting_subject = $usermail_setting_result['user_email_setting']['subject'];
            $usermail_setting_message = $usermail_setting_result['user_email_setting']['message'];
            $usermail_setting_option  = $usermail_setting_result['user_email_setting']['user-email-setting-option'];
            $usermail_setting_header  = isset( $usermail_setting_result['user_email_setting']['header'] ) ? $usermail_setting_result['user_email_setting']['header'] : $usermail_setting_header;
        }
        $ues_nonce = wp_create_nonce( 'ues-nonce' );

        $on_checked  = ( $usermail_setting_option === 'ON'  ) ? ' checked' : '';
        $off_checked = ( $usermail_setting_option === 'OFF' ) ? ' checked' : '';

        echo "<form id='form-user-email-setting' action='' method='post'>
<div class='lfb-es-card'>
  <div class='lfb-es-header'>
    <span class='lfb-es-icon lfb-es-icon-user'>" . lfb_svg( 'user', 22 ) . "</span>
    <div class='lfb-es-header-text'>
      <h3>" . esc_html__( 'User Email Notifications', 'lead-form-builder' ) . "</h3>
      <p>" . esc_html__( 'Send a confirmation email to the user after form submission. Requires an', 'lead-form-builder' ) . " <strong>" . esc_html__( 'Email', 'lead-form-builder' ) . "</strong> " . esc_html__( 'field in the form.', 'lead-form-builder' ) . "</p>
    </div>
  </div>
  <div class='lfb-es-body'>
    <div class='lfb-es-field'>
      <label class='lfb-es-label'>" . esc_html__( 'From', 'lead-form-builder' ) . " <span class='lfb-req'>*</span></label>
      <input name='user_email_setting[from]' required type='email' id='user_email_setting_from' value='" . esc_attr( $usermail_setting_from ) . "' class='lfb-es-input'>
      <span class='lfb-es-hint'>" . esc_html__( 'Sender email address for user confirmation emails.', 'lead-form-builder' ) . "</span>
    </div>
    <div class='lfb-es-field'>
      <label class='lfb-es-label'>" . esc_html__( 'Email Header', 'lead-form-builder' ) . " <span class='lfb-req'>*</span></label>
      <input name='user_email_setting[header]' required type='text' id='user_email_setting_header' value='" . esc_attr( $usermail_setting_header ) . "' class='lfb-es-input'>
      <span class='lfb-es-hint'>" . esc_html__( 'Headline displayed at the top of the confirmation email.', 'lead-form-builder' ) . "</span>
    </div>
    <div class='lfb-es-field'>
      <label class='lfb-es-label'>" . esc_html__( 'Subject', 'lead-form-builder' ) . " <span class='lfb-req'>*</span></label>
      <input name='user_email_setting[subject]' required type='text' id='user_email_setting_subject' value='" . esc_attr( $usermail_setting_subject ) . "' class='lfb-es-input'>
      <span class='lfb-es-hint'>" . esc_html__( 'Email subject line.', 'lead-form-builder' ) . "</span>
    </div>
    <div class='lfb-es-field'>
      <label class='lfb-es-label'>" . esc_html__( 'Message', 'lead-form-builder' ) . " <span class='lfb-req'>*</span></label>
      <textarea name='user_email_setting[message]' id='user_email_setting_message' rows='5' required class='lfb-es-input'>" . esc_textarea( $usermail_setting_message ) . "</textarea>
      <span class='lfb-es-hint'><code>[lf-new-form-data]</code> &mdash; " . esc_html__( 'All form entries', 'lead-form-builder' ) . " &nbsp;|&nbsp; <code>[name]</code> &mdash; " . esc_html__( 'User name', 'lead-form-builder' ) . "</span>
    </div>
    <div class='lfb-es-field'>
      <label class='lfb-es-label'>" . esc_html__( 'Send Confirmation Email', 'lead-form-builder' ) . "</label>
      <label class='lfb-es-radio-opt" . ( $usermail_setting_option === 'ON' ? ' lfb-es-radio-checked' : '' ) . "'>
        <input type='radio' name='user_email_setting[user-email-setting-option]'" . $on_checked . " value='ON'>
        <div class='lfb-es-radio-text'>
          <strong>" . esc_html__( 'Send Email', 'lead-form-builder' ) . "</strong>
          <span>" . esc_html__( 'Send a confirmation email to the user on form submit.', 'lead-form-builder' ) . "</span>
        </div>
      </label>
      <label class='lfb-es-radio-opt" . ( $usermail_setting_option === 'OFF' ? ' lfb-es-radio-checked' : '' ) . "'>
        <input type='radio' name='user_email_setting[user-email-setting-option]'" . $off_checked . " value='OFF'>
        <div class='lfb-es-radio-text'>
          <strong>" . esc_html__( 'Don\'t Send', 'lead-form-builder' ) . "</strong>
          <span>" . esc_html__( 'No email will be sent to the user.', 'lead-form-builder' ) . "</span>
        </div>
      </label>
    </div>
    <div class='lfb-es-footer'>
      <input type='hidden' name='user_email_setting[form-id]' value='" . intval( $this_form_id ) . "'>
      <input type='hidden' name='ues_nonce' value='" . esc_attr( $ues_nonce ) . "'>
      <div id='error-message-user-email-setting'></div>
      <button type='submit' class='lfb-es-save-btn'><i class='fa fa-check'></i> " . esc_html__( 'Save Settings', 'lead-form-builder' ) . "</button>
    </div>
  </div>
</div>
</form>";
    }

    function lfb_captcha_setting_form($this_form_id, $captcha_option)
    {
        $captcha_nonce   = wp_create_nonce( 'captcha-nonce' );
        $captcha_option_val = ! empty( $captcha_option ) ? $captcha_option : 'OFF';
        $captcha_sitekey = get_option( 'captcha-setting-sitekey' );
        $captcha_secret  = get_option( 'captcha-setting-secret' );

        echo '<div class="lfb-setting-two-col">
<div class="lfb-es-card">
  <div class="lfb-es-header">
    <span class="lfb-es-icon lfb-es-icon-captcha">' . lfb_svg( 'lock', 22 ) . '</span>
    <div class="lfb-es-header-text">
      <h3>' . esc_html__( 'reCAPTCHA Setup', 'lead-form-builder' ) . '</h3>
      <p>' . esc_html__( 'Protect your forms from spam and abuse.', 'lead-form-builder' ) . ' <a href="https://www.google.com/recaptcha/admin/create" target="_blank" class="lfb-es-link">' . esc_html__( 'Get your keys', 'lead-form-builder' ) . ' &rarr;</a></p>
    </div>
  </div>
  <div class="lfb-es-body">
    <form method="post" id="captcha-form" action="">
      <div class="lfb-es-field">
        <label class="lfb-es-label">' . esc_html__( 'Captcha Version', 'lead-form-builder' ) . '</label>
        <input type="text" class="lfb-es-input" value="reCAPTCHA v2 (Checkbox)" disabled readonly>
      </div>
      <div class="lfb-es-row lfb-es-two-col">
        <div class="lfb-es-field">
          <label class="lfb-es-label" for="sitekey">' . esc_html__( 'Site Key', 'lead-form-builder' ) . '</label>
          <input type="text" required value="' . esc_attr( $captcha_sitekey ) . '" id="sitekey" name="captcha-setting-sitekey" class="lfb-es-input">
        </div>
        <div class="lfb-es-field">
          <label class="lfb-es-label" for="secret">' . esc_html__( 'Secret Key', 'lead-form-builder' ) . '</label>
          <input type="text" required value="' . esc_attr( $captcha_secret ) . '" id="secret" name="captcha-setting-secret" class="lfb-es-input">
        </div>
      </div>
      <input type="hidden" name="captcha-keys" value="' . intval( $this_form_id ) . '">
      <input type="hidden" name="captcha_nonce" value="' . esc_attr( $captcha_nonce ) . '">
      <div class="lfb-es-footer">
        <div id="error-message-captcha-key"></div>
        <button type="submit" class="lfb-es-save-btn" id="captcha_save_settings"><i class="fa fa-check"></i> ' . esc_html__( 'Save Keys', 'lead-form-builder' ) . '</button>
      </div>
    </form>
  </div>
</div>';

        echo '<div class="lfb-es-card">
  <div class="lfb-es-header">
    <span class="lfb-es-icon lfb-es-icon-shield">' . lfb_svg( 'shield', 22 ) . '</span>
    <div class="lfb-es-header-text">
      <h3>' . esc_html__( 'Captcha On / Off', 'lead-form-builder' ) . '</h3>
      <p>' . esc_html__( 'Enable or disable reCAPTCHA for this specific form.', 'lead-form-builder' ) . '</p>
    </div>
  </div>
  <div class="lfb-es-body">
    <form name="" id="captcha-on-off-setting" method="post" action="">
      <div class="lfb-es-field">
        <label class="lfb-es-radio-opt' . ( $captcha_option_val === 'ON' ? ' lfb-es-radio-checked' : '' ) . '">
          <input type="radio" name="captcha-on-off-setting" ' . ( $captcha_option_val === 'ON' ? 'checked' : '' ) . ' value="ON">
          <div class="lfb-es-radio-text">
            <strong>' . esc_html__( 'Enable Captcha', 'lead-form-builder' ) . '</strong>
            <span>' . esc_html__( 'Show reCAPTCHA challenge on this form.', 'lead-form-builder' ) . '</span>
          </div>
        </label>
        <label class="lfb-es-radio-opt' . ( $captcha_option_val === 'OFF' ? ' lfb-es-radio-checked' : '' ) . '">
          <input type="radio" name="captcha-on-off-setting" ' . ( $captcha_option_val === 'OFF' ? 'checked' : '' ) . ' value="OFF">
          <div class="lfb-es-radio-text">
            <strong>' . esc_html__( 'Disable Captcha', 'lead-form-builder' ) . '</strong>
            <span>' . esc_html__( 'Hide reCAPTCHA for this form.', 'lead-form-builder' ) . '</span>
          </div>
        </label>
      </div>
      <input type="hidden" name="captcha_on_off_form_id" value="' . intval( $this_form_id ) . '">
      <input type="hidden" name="captcha_nonce" value="' . esc_attr( $captcha_nonce ) . '">
      <div class="lfb-es-footer">
        <div id="error-message-captcha-option"></div>
        <button type="submit" class="lfb-es-save-btn" id="captcha_on_off_form_id"><i class="fa fa-check"></i> ' . esc_html__( 'Save', 'lead-form-builder' ) . '</button>
      </div>
    </form>
  </div>
</div>
</div>';
    }

    function lfb_lead_setting_form($this_form_id, $lead_store_option)
    {
        $lead_store_option = ! empty( $lead_store_option ) ? intval( $lead_store_option ) : 2;
        $nonce             = wp_create_nonce( 'lrv-nonce' );

        $methods = array(
            1 => array(
                'icon'  => 'fa-envelope',
                'title' => esc_html__( 'Receive Leads in Email', 'lead-form-builder' ),
                'desc'  => esc_html__( 'Leads are sent directly to the configured admin email address.', 'lead-form-builder' ),
            ),
            2 => array(
                'icon'  => 'fa-database',
                'title' => esc_html__( 'Save Leads in Database', 'lead-form-builder' ),
                'desc'  => esc_html__( 'All leads are stored in the database and visible in the leads list.', 'lead-form-builder' ),
            ),
            3 => array(
                'icon'  => 'fa-check-circle',
                'title' => esc_html__( 'Email + Save in Database', 'lead-form-builder' ),
                'desc'  => esc_html__( 'Receive leads by email and also save them in the database simultaneously.', 'lead-form-builder' ),
            ),
        );

        $opts_html = '';
        foreach ( $methods as $val => $m ) {
            $checked    = ( $lead_store_option == $val ) ? ' checked' : '';
            $active_cls = ( $lead_store_option == $val ) ? ' lfb-es-radio-checked' : '';
            $opts_html .= '<label class="lfb-es-radio-opt lfb-setting-opt' . $active_cls . '">
          <input type="radio" name="data-recieve-method"' . $checked . ' value="' . $val . '">
          <span class="lfb-setting-opt-icon"><i class="fa ' . $m['icon'] . '"></i></span>
          <div class="lfb-es-radio-text">
            <strong>' . $m['title'] . '</strong>
            <span>' . $m['desc'] . '</span>
          </div>
        </label>';
        }

        echo '<div class="lfb-setting-two-col">
<div class="lfb-es-card">
  <div class="lfb-es-header">
    <span class="lfb-es-icon lfb-es-icon-lead">' . lfb_svg( 'download', 22 ) . '</span>
    <div class="lfb-es-header-text">
      <h3>' . esc_html__( 'Lead Receiving Method', 'lead-form-builder' ) . '</h3>
      <p>' . esc_html__( 'Choose how submitted leads are handled for this form.', 'lead-form-builder' ) . '</p>
    </div>
  </div>
  <div class="lfb-es-body">
    <form name="" id="lead-email-setting" method="post" action="">
      <div class="lfb-es-field">' . $opts_html . '</div>
      <input type="hidden" name="action-lead-setting" value="' . intval( $this_form_id ) . '">
      <input type="hidden" name="lrv_nonce_verify" value="' . esc_attr( $nonce ) . '">
      <div class="lfb-es-footer">
        <div id="error-message-lead-store"></div>
        <button type="submit" class="lfb-es-save-btn" id="advance_lead_setting"><i class="fa fa-check"></i> ' . esc_html__( 'Update', 'lead-form-builder' ) . '</button>
      </div>
    </form>
  </div>
</div>';

        global $wpdb;
        $th_save_db = new LFB_SAVE_DB( $wpdb );
        $table_name = LFB_FORM_FIELD_TBL;
        $prepare_10 = $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d LIMIT 1", $this_form_id );
        $posts      = $th_save_db->lfb_get_form_content( $prepare_10 );
        $successMsg = esc_html__( 'Thank you for your valuable feedback. It will help us serve you better.', 'lead-form-builder' );
        $redirectUrl = '';
        if ( isset( $posts[0]->multiData ) ) {
            $multidata   = maybe_unserialize( $posts[0]->multiData );
            $successMsg  = isset( $multidata['lfb_success_msg'] ) ? $multidata['lfb_success_msg'] : $successMsg;
            $redirectUrl = isset( $multidata['lfb_redirect_url'] ) ? $multidata['lfb_redirect_url'] : '';
        }
        $sm_nonce = wp_create_nonce( 'lfb-sm-nonce' );

        echo '<div class="lfb-es-card">
  <div class="lfb-es-header">
    <span class="lfb-es-icon lfb-es-icon-thankyou">' . lfb_svg( 'check-circle', 22 ) . '</span>
    <div class="lfb-es-header-text">
      <h3>' . esc_html__( 'Thank You Message', 'lead-form-builder' ) . '</h3>
      <p>' . esc_html__( 'Displayed to the visitor after the form is submitted successfully.', 'lead-form-builder' ) . '</p>
    </div>
  </div>
  <div class="lfb-es-body">
    <form name="" id="lfb-form-success-msg" method="post" action="">
      <div class="lfb-es-field">
        <label class="lfb-es-label">' . esc_html__( 'Success Message', 'lead-form-builder' ) . '</label>
        <textarea name="lfb_success_msg" id="lfb_success_msg" rows="5" class="lfb-es-input">' . esc_textarea( $successMsg ) . '</textarea>
        <span class="lfb-es-hint lfb-es-hint-info">' . esc_html__( 'This message is shown to the visitor after submitting the form.', 'lead-form-builder' ) . '</span>
      </div>
      <div class="lfb-es-field lfb-pro-field">
        <div class="lfb-pro-badge">' . lfb_svg( 'lock', 10 ) . ' ' . esc_html__( 'Pro', 'lead-form-builder' ) . '</div>
        <label class="lfb-es-label">' . esc_html__( 'Redirect URL', 'lead-form-builder' ) . '</label>
        <input name="lfb_redirect_url" id="lfb_redirect_url" type="url" class="lfb-es-input" placeholder="https://example.com/thank-you" value="' . esc_url( $redirectUrl ) . '" disabled>
        <span class="lfb-es-hint lfb-es-hint-info">' . esc_html__( 'Visitor will be redirected here after submitting. Leave empty to show the message above.', 'lead-form-builder' ) . '</span>
      </div>
      <input type="hidden" name="lfb_sm_form_id" value="' . intval( $this_form_id ) . '">
      <input type="hidden" name="lfb_sm_nonce" value="' . esc_attr( $sm_nonce ) . '">
      <div class="lfb-es-footer">
        <div id="error-message-success-msg"></div>
        <a href="https://themehunk.com/lead-form-builder-pro/" target="_blank" class="lfb-es-save-btn lfb-pro-btn">' . lfb_svg( 'lock', 13 ) . ' ' . esc_html__( 'Upgrade to Pro', 'lead-form-builder' ) . '</a>
        <button type="submit" class="lfb-es-save-btn"><i class="fa fa-check"></i> ' . esc_html__( 'Update', 'lead-form-builder' ) . '</button>
      </div>
    </form>
  </div>
</div>
</div>';
    }
}
