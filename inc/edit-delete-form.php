<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once('lf-db.php');
Class LFB_EDIT_DEL_FORM {

    /** SVG helpers */
    function lfbDragHandle() {
        return '<span class="lfb-drag-handle" title="' . esc_attr__( 'Drag to reorder', 'lead-form-builder' ) . '">' . lfb_svg( 'drag' ) . '</span>';
    }
    function lfbTrashIcon() {
        return lfb_svg( 'trash' );
    }
    function lfbDuplicateIcon() {
        return lfb_svg( 'duplicate' );
    }

    function lfb_edit_form_content( $form_action, $this_form_id ) {
        global $wpdb;
        $th_save_db  = new LFB_SAVE_DB( $wpdb );
        $table_name  = LFB_FORM_FIELD_TBL;
        $prepare_8   = $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d LIMIT 1", $this_form_id );
        $posts       = $th_save_db->lfb_get_form_content( $prepare_8 );

        $form_title              = '';
        $form_data_result        = array();
        $mail_setting_result     = '';
        $usermail_setting_result = '';
        $captcha_option          = '';
        $lead_store_option       = '';
        $all_form_fields         = '';

        if ( $posts ) {
            $form_title              = esc_html( $posts[0]->form_title );
            $form_data_result        = maybe_unserialize( $posts[0]->form_data );
            $mail_setting_result     = $posts[0]->mail_setting;
            $usermail_setting_result = $posts[0]->usermail_setting;
            $captcha_option          = $posts[0]->captcha_status;
            $lead_store_option       = esc_html( $posts[0]->storeType );
            $all_form_fields         = $this->lfb_create_form_fields_for_edit( $form_title, $form_data_result );
        }

        $form_message = '';
        if ( isset( $_GET['redirect'] ) ) {
            $rv = esc_html( $_GET['redirect'] );
            if ( $rv === 'create' ) {
                $form_message = '<div id="message" class="updated notice is-dismissible"><p>Form <strong>Saved</strong>.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">' . esc_html__( 'Dismiss this notice.', 'lead-form-builder' ) . '</span></button></div>';
            } elseif ( $rv === 'update' ) {
                $form_message = '<div id="message" class="updated notice is-dismissible"><p>Form <strong>Updated</strong>.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">' . esc_html__( 'Dismiss this notice.', 'lead-form-builder' ) . '</span></button></div>';
            }
        }

        $nonce      = wp_create_nonce( '_nonce_verify' );
        $update_url = 'admin.php?page=add-new-form&action=edit&redirect=update&formid=' . $this_form_id . '&_wpnonce=' . $nonce;

        $email_active = $captcha_active = $form_active = $_active = '';
        if ( isset( $_GET['email-setting'] ) ) {
            $email_active = 'nav-tab-active';
        } elseif ( isset( $_GET['captcha-setting'] ) ) {
            $captcha_active = 'nav-tab-active';
        } elseif ( isset( $_GET['form-setting'] ) ) {
            $form_active = 'nav-tab-active';
        } else {
            $_active = 'nav-tab-active';
        }

        echo '<div class="wrap">';
        lfb_admin_menu_header();
        echo wp_kses_post( $form_message );
        echo '<div class="nav-tab-wrapper">
            <a class="nav-tab edit-lead-form ' . esc_attr( $_active ) . '" href="#">' . esc_html__( 'Edit Form', 'lead-form-builder' ) . '</a>
            <a class="nav-tab lead-form-email-setting ' . esc_attr( $email_active ) . '" href="#">' . esc_html__( 'Email Setting', 'lead-form-builder' ) . '</a>
            <a class="nav-tab lead-form-captcha-setting ' . esc_attr( $captcha_active ) . '" href="#">' . esc_html__( 'Captcha Setting', 'lead-form-builder' ) . '</a>
            <a class="nav-tab lead-form-setting ' . esc_attr( $form_active ) . '" href="#">' . esc_html__( 'Setting', 'lead-form-builder' ) . '</a>
        </div>
        <div id="sections">
            <section>
                <div class="wrap">
                    <form method="post" action="' . esc_url( $update_url ) . '" id="new_lead_form">
                        <div class="lfb-form-title-wrap">
                            <label class="lfb-form-title-label">' . esc_html__( 'Form Title', 'lead-form-builder' ) . '</label>
                            <input type="text" class="new_form_heading" name="post_title" placeholder="' . esc_attr__( 'Enter title here', 'lead-form-builder' ) . '" value="' . esc_attr( $form_title ) . '" id="title" autocomplete="off">
                        </div>';
        $this->lfb_basic_form();
        echo $all_form_fields;
        echo '<p class="submit">
            <input type="submit" class="update_form button-primary" name="update_form" id="update_form" value="' . esc_attr__( 'Update Form', 'lead-form-builder' ) . '">
            <input type="hidden" class="update_form_id" name="update_form_id" id="update_form_id" value="' . intval( $this_form_id ) . '">
        </p>
        <input type="hidden" name="_wpnonce" value="' . esc_attr( $nonce ) . '" />
        </form>
        </div>
    </section>
    <section>';
        if ( is_admin() ) {
            $lf_email = new LFB_EmailSettingForm( $this_form_id );
            $lf_email->lfb_email_setting_form( $this_form_id, $mail_setting_result, $usermail_setting_result );
        }
        echo '</section><section>';
        if ( is_admin() ) {
            $lf_captcha = new LFB_EmailSettingForm( $this_form_id );
            $lf_captcha->lfb_captcha_setting_form( $this_form_id, $captcha_option );
        }
        echo '</section><section>';
        if ( is_admin() ) {
            $lf_setting = new LFB_EmailSettingForm( $this_form_id );
            $lf_setting->lfb_lead_setting_form( $this_form_id, $lead_store_option );
        }
        echo '</section></div></div>';
    }

    function lfb_delete_form_content( $form_action, $this_form_id, $page_id ) {
        $nonce = isset( $_REQUEST['_wpnonce'] ) ? $_REQUEST['_wpnonce'] : false;
        if ( wp_verify_nonce( $nonce, '_nonce_verify' ) ) {
            global $wpdb;
            $table_name = LFB_FORM_FIELD_TBL;
            $wpdb->update( $table_name, array( 'form_status' => 'Disable' ), array( 'id' => $this_form_id ) );
            $th_show_forms = new LFB_SHOW_FORMS();
            $th_show_forms->lfb_show_all_forms( $page_id );
        } else {
            echo 'Invalid URL';
        }
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
          <div id='sortable' class='lfb-sortable-cards'>";
    }

    function lfbFormField( $key ) {
        $fields = array(
            'name'      => 'Name',
            'email'     => 'Email',
            'message'   => 'Message',
            'dob'       => 'DOB(Date of Birth)',
            'date'      => 'Date',
            'text'      => 'Text (Single Line Text)',
            'textarea'  => 'Textarea (Multiple Line Text)',
            'htmlfield' => 'Content Area (Read only Text)',
            'url'       => 'Link (Website Url)',
            'number'    => 'Number (Only Numeric 0-9)',
            'upload'    => 'File Upload',
            'radio'     => 'Radio (Choose Single Option)',
            'option'    => 'Option (Choose Single Option)',
            'checkbox'  => 'Checkbox (Choose Multiple Option)',
            'terms'     => 'Checkbox (Terms & condition)',
        );
        return isset( $fields[ $key ] ) ? $fields[ $key ] : '';
    }

    function lfbFieldName( $fieldv, $fieldID ) {
        $fieldName = isset( $fieldv['field_name'] ) ? $fieldv['field_name'] : '';
        return '<div class="lfb-field-col">
            <label class="lfb-col-label">' . esc_html__( 'Field Label', 'lead-form-builder' ) . '</label>
            <input type="text" name="form_field_' . $fieldID . '[field_name]" id="field_name_' . $fieldID . '" value="' . esc_attr( $fieldName ) . '">
        </div>';
    }

    function lfbFieldTypeDefault( $fieldtype, $name, $fieldID ) {
        return '<div class="lfb-field-col">
            <label class="lfb-col-label">' . esc_html__( 'Field Type', 'lead-form-builder' ) . '</label>
            <select class="form_field_select" name="form_field_' . $fieldID . '[field_type][type]" id="field_type_' . $fieldID . '">
                <option value="' . esc_attr( $fieldtype ) . '" selected="selected">' . esc_html( $name ) . '</option>
            </select>
        </div>';
    }

    function lfbFieldDefaultValue( $fieldv, $fieldID, $fieldtype = '' ) {
        $defaultValue = isset( $fieldv['default_value'] ) ? $fieldv['default_value'] : '';
        $hide         = ( $fieldtype === 'terms' ) ? ' style="display:none;"' : '';
        return '<div class="lfb-field-col">
            <label class="lfb-col-label">' . esc_html__( 'Default Value', 'lead-form-builder' ) . '</label>
            <input' . $hide . ' type="text" class="default_value" name="form_field_' . $fieldID . '[default_value]" id="default_value_' . $fieldID . '" value="' . esc_attr( $defaultValue ) . '">
        </div>';
    }

    function lfbHtmlFieldValue( $fieldv, $fieldID ) {
        $defaultValue = isset( $fieldv['default_value'] ) ? $fieldv['default_value'] : '';
        return '<div class="lfb-field-col lfb-field-col-html">
            <label class="lfb-col-label">' . esc_html__( 'Content', 'lead-form-builder' ) . '</label>
            <div class="default_htmlfield_' . $fieldID . '" id="default_htmlfield">
                <textarea class="default_value default_htmlfield" name="form_field_' . $fieldID . '[default_value]" id="default_value_' . $fieldID . '">' . esc_textarea( $defaultValue ) . '</textarea>
            </div>
        </div>';
    }

    /** Combined placeholder + required as toggle switches */
    function lfbFieldOptions( $fieldv, $fieldID, $fieldtype = '' ) {
        $fieldPlaceholder = isset( $fieldv['default_placeholder'] ) ? $fieldv['default_placeholder'] : '';
        $checkedPH        = ( $fieldPlaceholder == 1 ) ? ' checked' : '';
        $fieldRequired    = isset( $fieldv['is_required'] ) ? $fieldv['is_required'] : '';
        $checkedReq       = ( $fieldRequired == 1 ) ? ' checked' : '';
        $hidePH           = in_array( $fieldtype, array( 'terms', 'radio', 'option', 'checkbox' ) ) ? ' style="display:none;"' : '';
        return '<div class="lfb-field-col lfb-toggles">
            <label class="lfb-col-label">' . esc_html__( 'Options', 'lead-form-builder' ) . '</label>
            <label class="lfb-toggle-wrap"' . $hidePH . '>
                <input type="checkbox" class="default_placeholder" name="form_field_' . $fieldID . '[default_placeholder]" id="default_placeholder_' . $fieldID . '" value="1"' . $checkedPH . '>
                <span class="lfb-toggle-sl"></span>
                <span class="lfb-toggle-txt">' . esc_html__( 'Placeholder', 'lead-form-builder' ) . '</span>
            </label>
            <label class="lfb-toggle-wrap">
                <input type="checkbox" name="form_field_' . $fieldID . '[is_required]" id="is_required_' . $fieldID . '" value="1"' . $checkedReq . '>
                <span class="lfb-toggle-sl"></span>
                <span class="lfb-toggle-txt">' . esc_html__( 'Required', 'lead-form-builder' ) . '</span>
            </label>
        </div>';
    }

    function lfbRemoveField( $fieldID ) {
        return '<div class="lfb-field-col lfb-field-col-action">
            <input type="hidden" value="' . $fieldID . '" name="form_field_' . $fieldID . '[field_id]">
            <button type="button" class="lfb-btn-duplicate-field" data-id="' . $fieldID . '" onclick="duplicate_form_field(' . $fieldID . ')" title="' . esc_attr__( 'Duplicate', 'lead-form-builder' ) . '">' . $this->lfbDuplicateIcon() . '</button>
            <button type="button" class="lfb-btn-remove-field" onclick="remove_form_fields(' . $fieldID . ')" title="' . esc_attr__( 'Remove', 'lead-form-builder' ) . '">' . $this->lfbTrashIcon() . '</button>
        </div>';
    }

    /** Submit card + add field bar + close form-builder-wrap.
     *  Caller must have already closed .append_new and #sortable. */
    function lfbAddField( $fieldv, $fieldID, $lastFieldID ) {
        $defaultValue = isset( $fieldv['default_value'] ) ? esc_attr( $fieldv['default_value'] ) : esc_attr__( 'SUBMIT', 'lead-form-builder' );

        $return  = '<div class="lfb-submit-card">';
        $return .= '<div class="lfb-field-inner">';
        $return .= '<div class="lfb-field-col">';
        $return .= '<label class="lfb-col-label">' . esc_html__( 'Button Label', 'lead-form-builder' ) . '</label>';
        $return .= '<input type="hidden" name="form_field_0[field_name]" value="submit">';
        $return .= '<input type="text" class="default_value" name="form_field_0[default_value]" id="default_value_0" value="' . $defaultValue . '">';
        $return .= '</div>';
        $return .= '<div class="lfb-field-col">';
        $return .= '<label class="lfb-col-label">' . esc_html__( 'Type', 'lead-form-builder' ) . '</label>';
        $return .= '<select class="form_field_select" name="form_field_0[field_type][type]" id="field_type_0">';
        $return .= '<option value="submit" selected="selected">' . esc_html__( 'Submit Button', 'lead-form-builder' ) . '</option>';
        $return .= '</select></div>';
        $return .= '<div class="lfb-field-col"></div>';
        $return .= '<div class="lfb-field-col">';
        $return .= '<input type="hidden" class="default_placeholder" name="form_field_0[default_placeholder]" value="0">';
        $return .= '<input type="hidden" name="form_field_0[is_required]" value="1">';
        $return .= '<input type="hidden" value="0" name="form_field_0[field_id]">';
        $return .= '</div></div></div>';

        $return .= '<div class="lfb-add-field-bar">';
        $return .= '<span class="add-field"><input type="button" class="button lf_addnew" name="add_new" id="add_new_' . $lastFieldID . '" onclick="add_new_form_fields(' . $lastFieldID . ')" value="' . esc_attr__( '+ Add Field', 'lead-form-builder' ) . '"></span>';
        $return .= '</div>';
        $return .= '</div>'; // close .lfb-form-builder-wrap
        return $return;
    }

    function lfbTypeText( $fieldv, $fieldtype, $fieldID ) {
        $value   = $this->lfbFormField( $fieldtype );
        $return  = $this->lfbFieldName( $fieldv, $fieldID );
        $return .= $this->lfbFieldTypeDefault( $fieldtype, $value, $fieldID );
        $return .= $this->lfbFieldDefaultValue( $fieldv, $fieldID, $fieldtype );
        $return .= $this->lfbFieldOptions( $fieldv, $fieldID, $fieldtype );
        $return .= $this->lfbRemoveField( $fieldID );
        return $return;
    }

    function lfbTypeTextarea( $fieldv, $fieldtype, $fieldID ) {
        $return  = $this->lfbFieldName( $fieldv, $fieldID );
        $return .= $this->lfbFieldTypeDefault( 'message', 'Message', $fieldID );
        $return .= $this->lfbFieldDefaultValue( $fieldv, $fieldID, $fieldtype );
        $return .= $this->lfbFieldOptions( $fieldv, $fieldID, $fieldtype );
        $return .= $this->lfbRemoveField( $fieldID );
        return $return;
    }

    function lfbhtmlfield( $fieldv, $fieldtype, $fieldID ) {
        $return  = $this->lfbFieldName( $fieldv, $fieldID );
        $return .= $this->lfbFieldTypeDefault( 'htmlfield', esc_html__( 'Content Area (Read only Text)', 'lead-form-builder' ), $fieldID );
        $return .= $this->lfbHtmlFieldValue( $fieldv, $fieldID );
        $return .= '<div class="lfb-field-col"></div>';
        $return .= $this->lfbRemoveField( $fieldID );
        return $return;
    }

    function lfbSelectOption( $fieldv, $fieldtype, $fieldID ) {
        $optionField = $isChecked = $return = '';
        $lastFieldID = 0;
        unset( $fieldtype['type'] );
        foreach ( $fieldtype as $key => $value ) {
            $checkboxId = str_replace( 'field_', '', $key );
            $checked    = ( isset( $fieldv['default_value']['field'] ) && $fieldv['default_value']['field'] == $checkboxId ) ? ' checked' : '';
            $fieldMinus = '<button type="button" class="lfb-choice-del lf_minus" id="delete_option_' . $checkboxId . '" onclick="delete_option_fields(' . $fieldID . ',' . $checkboxId . ')"><i class="fa fa-times" aria-hidden="true"></i></button>';
            if ( $lastFieldID < $checkboxId ) {
                $lastFieldID = $checkboxId;
            }
            $childOption  = '<input type="text" class="input_option_val" name="form_field_' . $fieldID . '[field_type][field_' . $checkboxId . ']" id="option_field_' . $checkboxId . '" placeholder="' . esc_attr__( 'Choice ' . $checkboxId, 'lead-form-builder' ) . '" value="' . esc_attr( $value ) . '">';
            $isChecked   .= '<div class="lfb-default-choice" id="default_option_value_' . $checkboxId . '"><label><input type="radio" class="checked" name="form_field_' . $fieldID . '[default_value][field]" id="default_option_value_' . $checkboxId . '" value="' . $checkboxId . '"' . $checked . '> ' . esc_html( $value ) . '</label></div>';
            $optionField .= '<div class="lfb-choice-row">' . $childOption . $fieldMinus . '</div>';
        }
        $fieldPlus    = '<button type="button" class="lfb-add-choice-btn lf_plus" id="add_new_option_' . $lastFieldID . '" onclick="add_new_option_fields(' . $fieldID . ',' . $lastFieldID . ')">+ ' . esc_html__( 'Add Choice', 'lead-form-builder' ) . '</button>';
        $optionField .= $fieldPlus;

        $return .= $this->lfbFieldName( $fieldv, $fieldID );
        $return .= '<div class="lfb-field-col">
            <label class="lfb-col-label">' . esc_html__( 'Field Type', 'lead-form-builder' ) . '</label>
            <select class="form_field_select" name="form_field_' . $fieldID . '[field_type][type]" id="field_type_' . $fieldID . '">
                <option value="option" selected="selected">' . esc_html__( 'Option (Choose Single Option)', 'lead-form-builder' ) . '</option>
            </select>
            <div class="add_radio_checkbox_' . $fieldID . '" id="add_radio_checkbox">
                <div class="lfb-choices-wrap" id="add_option">' . $optionField . '</div>
            </div>
        </div>
        <div class="lfb-field-col">
            <label class="lfb-col-label">' . esc_html__( 'Default Value', 'lead-form-builder' ) . '</label>
            <input type="hidden" class="default_value" name="form_field_' . $fieldID . '[default_value]" id="default_value_' . $fieldID . '" value="" disabled="disabled">
            <div class="add_default_radio_checkbox_' . $fieldID . ' lfb-default-choices-wrap" id="add_default_radio_checkbox">
                <div class="" id="default_add_option">' . $isChecked . '</div>
            </div>
        </div>';
        $return .= $this->lfbFieldOptions( $fieldv, $fieldID, 'option' );
        $return .= $this->lfbRemoveField( $fieldID );
        return $return;
    }

    function lfbRadio( $fieldv, $fieldtype, $fieldID ) {
        $optionField = $isChecked = $return = '';
        $lastFieldID = 0;
        unset( $fieldtype['type'] );
        foreach ( $fieldtype as $key => $value ) {
            $checkboxId = str_replace( 'field_', '', $key );
            $checked    = ( isset( $fieldv['default_value']['field'] ) && $fieldv['default_value']['field'] == $checkboxId ) ? ' checked' : '';
            $fieldMinus = '<button type="button" class="lfb-choice-del lf_minus" id="delete_radio_' . $checkboxId . '" onclick="delete_radio_fields(' . $fieldID . ',' . $checkboxId . ')"><i class="fa fa-times" aria-hidden="true"></i></button>';
            if ( $lastFieldID < $checkboxId ) {
                $lastFieldID = $checkboxId;
            }
            $childOption  = '<input type="text" class="input_radio_val" name="form_field_' . $fieldID . '[field_type][field_' . $checkboxId . ']" id="radio_field_' . $checkboxId . '" placeholder="' . esc_attr__( 'Choice ' . $checkboxId, 'lead-form-builder' ) . '" value="' . esc_attr( $value ) . '">';
            $isChecked   .= '<div class="lfb-default-choice" id="default_radio_value_' . $checkboxId . '"><label><input type="radio" class="checked" name="form_field_' . $fieldID . '[default_value][field]" id="default_radio_value_' . $checkboxId . '" value="' . $checkboxId . '"' . $checked . '> ' . esc_html( $value ) . '</label></div>';
            $optionField .= '<div class="lfb-choice-row">' . $childOption . $fieldMinus . '</div>';
        }
        $fieldPlus    = '<button type="button" class="lfb-add-choice-btn lf_plus" id="add_new_radio_' . $lastFieldID . '" onclick="add_new_radio_fields(' . $fieldID . ',' . $lastFieldID . ')">+ ' . esc_html__( 'Add Choice', 'lead-form-builder' ) . '</button>';
        $optionField .= $fieldPlus;

        $return .= $this->lfbFieldName( $fieldv, $fieldID );
        $return .= '<div class="lfb-field-col">
            <label class="lfb-col-label">' . esc_html__( 'Field Type', 'lead-form-builder' ) . '</label>
            <select class="form_field_select" name="form_field_' . $fieldID . '[field_type][type]" id="field_type_' . $fieldID . '">
                <option value="radio" selected="selected">' . esc_html__( 'Radio (Choose Single Option)', 'lead-form-builder' ) . '</option>
            </select>
            <div class="add_radio_checkbox_' . $fieldID . '" id="add_radio_checkbox">
                <div class="lfb-choices-wrap" id="add_radio">' . $optionField . '</div>
            </div>
        </div>
        <div class="lfb-field-col">
            <label class="lfb-col-label">' . esc_html__( 'Default Value', 'lead-form-builder' ) . '</label>
            <input type="hidden" class="default_value" name="form_field_' . $fieldID . '[default_value]" id="default_value_' . $fieldID . '" value="" disabled="disabled">
            <div class="add_default_radio_checkbox_' . $fieldID . ' lfb-default-choices-wrap" id="add_default_radio_checkbox">
                <div class="" id="default_add_radio">' . $isChecked . '</div>
            </div>
        </div>';
        $return .= $this->lfbFieldOptions( $fieldv, $fieldID, 'radio' );
        $return .= $this->lfbRemoveField( $fieldID );
        return $return;
    }

    function lfbCheckbox( $fieldv, $fieldtype, $fieldID ) {
        $checkboxField = $isChecked = $return = '';
        $lastFieldID   = 0;
        unset( $fieldtype['type'] );
        foreach ( $fieldtype as $key => $value ) {
            $checkboxId    = str_replace( 'field_', '', $key );
            $checked       = isset( $fieldv['default_value'][ $key ] ) ? ' checked' : '';
            $fieldMinus    = '<button type="button" class="lfb-choice-del lf_minus" id="delete_checkbox_' . $checkboxId . '" onclick="delete_checkbox_fields(' . $fieldID . ',' . $checkboxId . ')"><i class="fa fa-times" aria-hidden="true"></i></button>';
            if ( $lastFieldID < $checkboxId ) {
                $lastFieldID = $checkboxId;
            }
            $childCheckbox  = '<input type="text" class="input_checkbox_val" name="form_field_' . $fieldID . '[field_type][field_' . $checkboxId . ']" id="checkbox_field_' . $checkboxId . '" placeholder="' . esc_attr__( 'Choice ' . $checkboxId, 'lead-form-builder' ) . '" value="' . esc_attr( $value ) . '">';
            $isChecked     .= '<div class="lfb-default-choice" id="default_checkbox_value_' . $checkboxId . '"><label><input type="checkbox" class="checked" name="form_field_' . $fieldID . '[default_value][field_' . $checkboxId . ']" id="default_checkbox_val_' . $checkboxId . '" value="1"' . $checked . '> ' . esc_html( $value ) . '</label></div>';
            $checkboxField .= '<div class="lfb-choice-row">' . $childCheckbox . $fieldMinus . '</div>';
        }
        $fieldPlus     = '<button type="button" class="lfb-add-choice-btn lf_plus" id="add_new_checkbox_' . $lastFieldID . '" onclick="add_new_checkbox_fields(' . $fieldID . ',' . $lastFieldID . ')">+ ' . esc_html__( 'Add Choice', 'lead-form-builder' ) . '</button>';
        $checkboxField .= $fieldPlus;

        $return .= $this->lfbFieldName( $fieldv, $fieldID );
        $return .= '<div class="lfb-field-col">
            <label class="lfb-col-label">' . esc_html__( 'Field Type', 'lead-form-builder' ) . '</label>
            <select class="form_field_select" name="form_field_' . $fieldID . '[field_type][type]" id="field_type_' . $fieldID . '">
                <option value="checkbox" selected="selected">' . esc_html__( 'Checkbox (Choose Multiple Option)', 'lead-form-builder' ) . '</option>
            </select>
            <div class="add_radio_checkbox_' . $fieldID . '" id="add_radio_checkbox">
                <div class="lfb-choices-wrap" id="add_checkbox">' . $checkboxField . '</div>
            </div>
        </div>
        <div class="lfb-field-col">
            <label class="lfb-col-label">' . esc_html__( 'Default Value', 'lead-form-builder' ) . '</label>
            <input type="hidden" class="default_value" name="form_field_' . $fieldID . '[default_value]" id="default_value_' . $fieldID . '" value="" disabled="disabled">
            <div class="add_default_radio_checkbox_' . $fieldID . ' lfb-default-choices-wrap" id="add_default_radio_checkbox">
                <div class="" id="default_add_checkbox">' . $isChecked . '</div>
            </div>
        </div>';
        $return .= $this->lfbFieldOptions( $fieldv, $fieldID, 'checkbox' );
        $return .= $this->lfbRemoveField( $fieldID );
        return $return;
    }

    function lfbFieldType( $fieldv, $fieldID ) {
        $text     = array( 'name', 'email', 'url', 'number', 'text', 'date', 'dob', 'upload', 'terms' );
        $textarea = array( 'message', 'textarea' );
        $fieldtype = $fieldv['field_type'];
        $fType     = $fieldv['field_type']['type'];
        if ( $fType === 'checkbox' ) {
            return $this->lfbCheckbox( $fieldv, $fieldtype, $fieldID );
        } elseif ( $fType === 'option' ) {
            return $this->lfbSelectOption( $fieldv, $fieldtype, $fieldID );
        } elseif ( $fType === 'radio' ) {
            return $this->lfbRadio( $fieldv, $fieldtype, $fieldID );
        } elseif ( $fType === 'htmlfield' ) {
            return $this->lfbhtmlfield( $fieldv, $fieldtype, $fieldID );
        } elseif ( in_array( $fType, $text ) ) {
            return $this->lfbTypeText( $fieldv, $fType, $fieldID );
        } elseif ( in_array( $fType, $textarea ) ) {
            return $this->lfbTypeTextarea( $fieldv, $fType, $fieldID );
        }
        return '';
    }

    function lfb_create_form_fields_for_edit( $form_title, $form_data_result ) {
        $fieldRow      = '';
        $addButtonData = null;
        $lastFieldID   = 0;

        // Pre-calculate max non-zero field ID
        foreach ( $form_data_result as $fieldv ) {
            $fid = isset( $fieldv['field_id'] ) ? intval( $fieldv['field_id'] ) : 0;
            if ( $fid > $lastFieldID ) $lastFieldID = $fid;
        }

        foreach ( $form_data_result as $fieldv ) {
            $fieldID = isset( $fieldv['field_id'] ) ? intval( $fieldv['field_id'] ) : 0;
            if ( $fieldID === 0 ) {
                $addButtonData = $fieldv;
            } else {
                $inner     = $this->lfbFieldType( $fieldv, $fieldID );
                $fieldRow .= '<div class="lfb-field-card" id="form_field_row_' . $fieldID . '">'
                    . $this->lfbDragHandle()
                    . '<div class="lfb-field-inner">' . $inner . '</div>'
                    . '</div>';
            }
        }

        $addButton = $addButtonData ? $this->lfbAddField( $addButtonData, 0, $lastFieldID ) : '';

        // .append_new is self-contained; close #sortable before submit card
        return '<div class="append_new">' . $fieldRow . '</div>'
            . '</div>' // close #sortable
            . $addButton;
    }

} // class end
