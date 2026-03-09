<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once('lf-db.php');
require_once('edit-delete-form.php');

function lfb_create_form_sanitize( $form_data ) {
    $fieldArr  = array( 'name', 'email', 'message', 'dob', 'date', 'url', 'text', 'textarea', 'number', 'submit' );
    $field_rco = array( 'option', 'checkbox', 'radio' );
    foreach ( $form_data as $key => $value ) {
        if ( in_array( $value['field_type']['type'], $fieldArr ) ) {
            $form_data[ $key ]['field_name']    = sanitize_title( $value['field_name'] );
            $form_data[ $key ]['default_value'] = sanitize_title( $value['default_value'] );
            $form_data[ $key ]['field_id']      = intval( $value['field_id'] );
            if ( isset( $value['default_placeholder'] ) ) {
                $form_data[ $key ]['default_placeholder'] = intval( $value['default_placeholder'] );
            }
            if ( isset( $value['is_required'] ) ) {
                $form_data[ $key ]['is_required'] = intval( $value['is_required'] );
            }
        } elseif ( $value['field_type']['type'] === 'htmlfield' ) {
            $form_data[ $key ]['field_name']    = wp_kses_post( $value['field_name'] );
            $form_data[ $key ]['default_value'] = wp_kses_post( $value['default_value'] );
            $form_data[ $key ]['field_id']      = intval( $value['field_id'] );
            if ( isset( $value['is_required'] ) ) {
                $form_data[ $key ]['is_required'] = intval( $value['is_required'] );
            }
        } elseif ( in_array( $value['field_type']['type'], $field_rco ) ) {
            foreach ( $value['field_type'] as $fkey => $fvalue ) {
                $form_data[ $key ]['field_type'][ $fkey ] = sanitize_text_field( $fvalue );
            }
            $form_data[ $key ]['field_name'] = sanitize_text_field( $value['field_name'] );
            $form_data[ $key ]['field_id']   = intval( $value['field_id'] );
            if ( isset( $value['is_required'] ) ) {
                $form_data[ $key ]['is_required'] = intval( $value['is_required'] );
            }
            if ( isset( $form_data[ $key ]['default_value']['field'] ) && in_array( $value['field_type']['type'], array( 'radio', 'option' ) ) ) {
                $form_data[ $key ]['default_value']['field'] = intval( $value['default_value']['field'] );
            } elseif ( isset( $form_data[ $key ]['default_value']['field'] ) && $value['field_type']['type'] === 'checkbox' ) {
                foreach ( $form_data[ $key ]['default_value'] as $ckey => $cvalue ) {
                    $form_data[ $key ]['default_value'][ $ckey ] = intval( $cvalue );
                }
            }
        }
    }
    return $form_data;
}

if ( sanitize_text_field( isset( $_POST['save_form'] ) ) && wp_verify_nonce( $_REQUEST['_wpnonce'], '_nonce_verify' ) ) {
    // Collect form_field_* keys directly from $_POST
    $form_data = array();
    foreach ( $_POST as $key => $value ) {
        if ( strpos( $key, 'form_field_' ) === 0 && is_array( $value ) ) {
            $form_data[ $key ] = $value;
        }
    }

    $title = sanitize_text_field( $_POST['post_title'] );
    $form_data = maybe_serialize( lfb_create_form_sanitize( $form_data ) );

    global $wpdb;
    $table_name = LFB_FORM_FIELD_TBL;
    $wpdb->query( $wpdb->prepare(
        "INSERT INTO $table_name ( form_title, form_data, date ) VALUES ( %s, %s, %s )",
        $title, $form_data, date( 'Y/m/d g:i:s' )
    ) );

    $nonce_val = isset( $_REQUEST['_wpnonce'] ) ? sanitize_text_field( $_REQUEST['_wpnonce'] ) : '';
    $rd_url    = admin_url() . 'admin.php?page=add-new-form&action=edit&redirect=create&formid=' . $wpdb->insert_id . '&_wpnonce=' . $nonce_val;
}

Class LFB_AddNewForm {

    function lfb_add_new_form() {
        echo '<div class="wrap">';

        echo '<h2 class="nav-tab-wrapper">
            <a class="nav-tab nav-tab-active lead-form-create-form" href="#">' . esc_html__( 'Create Form', 'lead-form-builder' ) . '</a>
            <a class="nav-tab lead-form-email-setting" href="#">' . esc_html__( 'Email Setting', 'lead-form-builder' ) . '</a>
            <a class="nav-tab lead-form-captcha-setting" href="#">' . esc_html__( 'Captcha Setting', 'lead-form-builder' ) . '</a>
            <a class="nav-tab lead-form-setting" href="#">' . esc_html__( 'Setting', 'lead-form-builder' ) . '</a>
        </h2>
        <div id="sections">
            <section>';
        if ( is_admin() ) {
            $this->lfb_add_form_setting();
        }
        echo '</section>
            <section>';
        if ( is_admin() ) {
            echo '<div class="wrap"><div class="infobox"><h1>' . esc_html__( 'Email Setting', 'lead-form-builder' ) . '</h1></div>
            <br class="clear"><div class="inside setting_section"><div class="card">
            <form name="" id="new-lead-email-setting" method="post" action="">
            <p class="sec_head">' . esc_html__( 'Please create and save your Lead Form to do these settings.', 'lead-form-builder' ) . '</p>
            </form></div></div></div>';
        }
        echo '</section>
            <section>';
        if ( is_admin() ) {
            echo '<div class="wrap"><div class="infobox"><h1>' . esc_html__( 'Captcha Setting', 'lead-form-builder' ) . '</h1></div>
            <br class="clear"><div class="inside setting_section"><div class="card">
            <form name="" id="new-captcha-setting" method="post" action="">
            <p class="sec_head">' . esc_html__( 'Please create and save your Lead Form to do these settings.', 'lead-form-builder' ) . '</p>
            </form></div></div></div>';
        }
        echo '</section><section>';
        if ( is_admin() ) {
            echo '<div class="wrap"><div class="infobox"><h1>' . esc_html__( 'Lead Receiving Method', 'lead-form-builder' ) . '</h1></div>
            <br class="clear"><div class="inside setting_section"><div class="card">
            <form name="" id="new-lead-form-setting" method="post" action="">
            <p class="sec_head">' . esc_html__( 'Please create and save your Lead Form to do these settings.', 'lead-form-builder' ) . '</p>
            </form></div></div></div>';
        }
        echo '</section></div></div>';
    }

    function lfb_add_form_setting() {
        $nonce      = wp_create_nonce( '_nonce_verify' );
        $create_url = 'admin.php?page=add-new-form&action=edit&redirect=create&_wpnonce=' . $nonce;

        echo '<div>
            <form method="post" action="' . esc_url( $create_url ) . '" id="new_lead_form">
                <div class="lfb-form-title-wrap">
                    <label class="lfb-form-title-label">' . esc_html__( 'Form Title', 'lead-form-builder' ) . '</label>
                    <input type="text" class="new_form_heading" name="post_title" placeholder="' . esc_attr__( 'Enter title here', 'lead-form-builder' ) . '" value="" id="title" autocomplete="off">
                    <input type="hidden" name="_wpnonce" value="' . esc_attr( $nonce ) . '" />
                </div>';
        $this->lfb_basic_form();
        $this->lfb_form_first_fields();
        $this->lfb_form_last_fields();
        echo '<p class="submit">
            <input type="submit" class="save_form button-primary" name="save_form" id="save_form" value="' . esc_attr__( 'Save Form', 'lead-form-builder' ) . '">
        </p>
        </form>
        <div id="message-box-error" class="message-box-error"></div>
        </div>';
    }

    function lfb_basic_form() {
        echo "<div class='lfb-form-builder-wrap' id='wpth_add_form'>
          <div class='lfb-fields-header'>
            <h2 class='sec_head'>" . esc_html__( 'Form Fields', 'lead-form-builder' ) . "</h2>
            <span style='font-size:11px;color:#9399c4;'>" . esc_html__( 'Drag rows to reorder', 'lead-form-builder' ) . "</span>
          </div>
          <div class='lfb-fields-columns'>
            <span>" . esc_html__( 'Field Label', 'lead-form-builder' ) . "</span>
            <span>" . esc_html__( 'Field Type', 'lead-form-builder' ) . "</span>
            <span>" . esc_html__( 'Default Value', 'lead-form-builder' ) . "</span>
            <span>" . esc_html__( 'Options', 'lead-form-builder' ) . "</span>
            <span></span>
          </div>
          <div id='sortable' class='lfb-sortable-cards'>
            <div class='append_new'>";
    }

    function lfb_form_first_fields() {
        $drag_svg = lfb_svg( 'drag' );
        $dup_svg  = lfb_svg( 'duplicate' );
        $del_svg  = lfb_svg( 'trash' );
        echo "<div class='lfb-field-card' id='form_field_row_1'>
            <span class='lfb-drag-handle' title='" . esc_attr__( 'Drag to reorder', 'lead-form-builder' ) . "'>" . $drag_svg . "</span>
            <div class='lfb-field-inner'>
                <div class='lfb-field-col'>
                    <label class='lfb-col-label'>" . esc_html__( 'Field Label', 'lead-form-builder' ) . "</label>
                    <input type='text' name='form_field_1[field_name]' id='field_name_1' value=''>
                </div>
                <div class='lfb-field-col'>
                    <label class='lfb-col-label'>" . esc_html__( 'Field Type', 'lead-form-builder' ) . "</label>
                    <select class='form_field_select' name='form_field_1[field_type][type]' id='field_type_1'>
                        <option value='select'>" . esc_html__( 'Select Field Type', 'lead-form-builder' ) . "</option>
                        <option value='name'>" . esc_html__( 'Name', 'lead-form-builder' ) . "</option>
                        <option value='email'>" . esc_html__( 'Email', 'lead-form-builder' ) . "</option>
                        <option value='message'>" . esc_html__( 'Message', 'lead-form-builder' ) . "</option>
                        <option value='dob'>" . esc_html__( 'DOB', 'lead-form-builder' ) . "</option>
                        <option value='date'>" . esc_html__( 'Date', 'lead-form-builder' ) . "</option>
                        <option value='text'>" . esc_html__( 'Text (Single Line Text)', 'lead-form-builder' ) . "</option>
                        <option value='textarea'>" . esc_html__( 'Textarea (Multiple Line Text)', 'lead-form-builder' ) . "</option>
                        <option value='htmlfield'>" . esc_html__( 'Content Area (Read only Text)', 'lead-form-builder' ) . "</option>
                        <option value='url'>" . esc_html__( 'Url (Website url)', 'lead-form-builder' ) . "</option>
                        <option value='number'>" . esc_html__( 'Number (Only Numeric 0-9)', 'lead-form-builder' ) . "</option>
                        <option value='radio'>" . esc_html__( 'Radio (Choose Single Option)', 'lead-form-builder' ) . "</option>
                        <option value='option'>" . esc_html__( 'Option (Choose Single Option)', 'lead-form-builder' ) . "</option>
                        <option value='checkbox'>" . esc_html__( 'Checkbox (Choose Multiple Option)', 'lead-form-builder' ) . "</option>
                        <option value='terms'>" . esc_html__( 'Checkbox (Terms & condition)', 'lead-form-builder' ) . "</option>
                    </select>
                    <div class='add_radio_checkbox_1' id='add_radio_checkbox'>
                        <div class='' id='add_radio'></div>
                        <div class='' id='add_checkbox'></div>
                        <div class='' id='add_option'></div>
                    </div>
                </div>
                <div class='lfb-field-col'>
                    <label class='lfb-col-label'>" . esc_html__( 'Default Value', 'lead-form-builder' ) . "</label>
                    <input type='text' class='default_value' name='form_field_1[default_value]' id='default_value_1' value=''>
                    <div class='default_htmlfield_1' id='default_htmlfield'></div>
                    <div class='add_default_radio_checkbox_1' id='add_default_radio_checkbox'>
                        <div class='' id='default_add_radio'></div>
                        <div class='' id='default_add_checkbox'></div>
                        <div class='' id='default_add_option'></div>
                    </div>
                    <div class='default_terms_1' id='default_terms'></div>
                </div>
                <div class='lfb-field-col lfb-toggles'>
                    <label class='lfb-col-label'>" . esc_html__( 'Options', 'lead-form-builder' ) . "</label>
                    <label class='lfb-toggle-wrap'>
                        <input type='checkbox' class='default_placeholder' name='form_field_1[default_placeholder]' id='default_placeholder_1' value='1'>
                        <span class='lfb-toggle-sl'></span>
                        <span class='lfb-toggle-txt'>" . esc_html__( 'Placeholder', 'lead-form-builder' ) . "</span>
                    </label>
                    <label class='lfb-toggle-wrap'>
                        <input type='checkbox' name='form_field_1[is_required]' id='is_required_1' value='1'>
                        <span class='lfb-toggle-sl'></span>
                        <span class='lfb-toggle-txt'>" . esc_html__( 'Required', 'lead-form-builder' ) . "</span>
                    </label>
                </div>
                <div class='lfb-field-col lfb-field-col-action'>
                    <input type='hidden' value='1' name='form_field_1[field_id]'>
                    <button type='button' class='lfb-btn-duplicate-field' data-id='1' onclick='duplicate_form_field(1)' title='" . esc_attr__( 'Duplicate', 'lead-form-builder' ) . "'>" . $dup_svg . "</button>
                    <button type='button' class='lfb-btn-remove-field' onclick='remove_form_fields(1)' title='" . esc_attr__( 'Remove', 'lead-form-builder' ) . "'>" . $del_svg . "</button>
                </div>
            </div>
        </div>";
    }

    function lfb_form_last_fields() {
        $submit_label = esc_attr__( 'SUBMIT', 'lead-form-builder' );
        // Close .append_new and #sortable (opened in lfb_basic_form + lfb_form_first_fields)
        echo "</div></div>";
        // Submit button field card
        echo "<div class='lfb-submit-card'>
            <div class='lfb-field-inner'>
                <div class='lfb-field-col'>
                    <label class='lfb-col-label'>" . esc_html__( 'Button Label', 'lead-form-builder' ) . "</label>
                    <input type='hidden' name='form_field_0[field_name]' value='submit'>
                    <input type='text' class='default_value' name='form_field_0[default_value]' id='default_value_0' value='" . $submit_label . "'>
                </div>
                <div class='lfb-field-col'>
                    <label class='lfb-col-label'>" . esc_html__( 'Type', 'lead-form-builder' ) . "</label>
                    <select class='form_field_select' name='form_field_0[field_type][type]' id='field_type_0'>
                        <option value='submit'>" . esc_html__( 'Submit Button', 'lead-form-builder' ) . "</option>
                    </select>
                </div>
                <div class='lfb-field-col'></div>
                <div class='lfb-field-col'>
                    <input type='hidden' class='default_placeholder' name='form_field_0[default_placeholder]' value='0'>
                    <input type='hidden' name='form_field_0[is_required]' value='1'>
                    <input type='hidden' value='0' name='form_field_0[field_id]'>
                </div>
            </div>
        </div>
        <div class='lfb-add-field-bar'>
            <span class='add-field'><input type='button' class='button lf_addnew' name='add_new' id='add_new_1' onclick='add_new_form_fields(1)' value='" . esc_attr__( '+ Add Field', 'lead-form-builder' ) . "'></span>
        </div>
        </div>"; // close .lfb-form-builder-wrap
    }

}
