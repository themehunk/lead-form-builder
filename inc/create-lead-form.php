<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
require_once('lf-db.php');
require_once('edit-delete-form.php');
if (sanitize_text_field(isset($_POST['save_form'])) && wp_verify_nonce($_REQUEST['_wpnonce'],'_nonce_verify')) {
    $form_data=$_POST;
    $title = sanitize_text_field($_POST['post_title']);
    unset($_POST['post_title']);
    unset($_POST['save_form']);
    unset($_POST['_wpnonce']);
    $form_data= maybe_serialize(lfb_array_stripslash($_POST));
    global $wpdb;
    $table_name = LFB_FORM_FIELD_TBL;

$wpdb->query( $wpdb->prepare( 
  "INSERT INTO $table_name ( form_title, form_data, date ) VALUES ( %s, %s, %s )",
   $title, $form_data, date('Y/m/d g:i:s') ) );

    $rd_url = admin_url().'admin.php?page=add-new-form&action=edit&redirect=create&formid='.$wpdb->insert_id.'&_wpnonce='.$_REQUEST['_wpnonce'];
    wp_redirect($rd_url);
}
Class LFB_AddNewForm {
function lfb_add_new_form(){
	echo '<div class="wrap">
        <h2>Add New From</h2>
        <h2 class="nav-tab-wrapper">
            <a class="nav-tab nav-tab-active lead-form-create-form" href="#">'.esc_html__('Create Form','lead-form-builder').'</a>
            <a class="nav-tab lead-form-email-setting" href="#">'.esc_html__('Email Setting','lead-form-builder').'</a>
            <a class="nav-tab lead-form-captcha-setting" href="#">'.esc_html__('Captcha Setting','lead-form-builder').'</a>
            <a class="nav-tab lead-form-setting" href="#">'.esc_html__('Setting','lead-form-builder').'</a>
       </h2>
        <div id="sections">
            <section>';
                if (is_admin()) {
                   $this->lfb_add_form_setting();
                }
            echo '</section>
            <section>';
                if (is_admin()) {
                    echo '<div class="wrap">
<div class="infobox">
<h1>Email Setting</h1></div>
<br class="clear"><div class="inside setting_section">
           <div class="card">
                <form name="" id="new-lead-email-setting" method="post" action="">
                <p class="sec_head">'.esc_html__('Please create and save your Lead Form to do these settings.','lead-form-builder').'</p>  
                </form>            
            </div>
            </div></div>';
                }
           echo '</section>
           <section>';
           if (is_admin()) {
                    echo '<div class="wrap">
<div class="infobox">
<h1>Captcha Setting</h1></div>
<br class="clear"><div class="inside setting_section">
           <div class="card">
                <form name="" id="new-captcha-setting" method="post" action="">
                <p class="sec_head">'.esc_html__('Please create and save your Lead Form to do these settings.','lead-form-builder').'</p>  
                </form>            
            </div>
            </div></div>';
                }
           echo '</section><section>';
           if (is_admin()) {
                    echo '<div class="wrap">
<div class="infobox">
<h1>Lead Receiving Method</h1></div>
<br class="clear"><div class="inside setting_section">
           <div class="card">
                <form name="" id="new-lead-form-setting" method="post" action="">
                <p class="sec_head">'.esc_html__('Please create and save your Lead Form to do these settings.','lead-form-builder').'</p>  
                </form>            
            </div>
            </div></div>';
                }
           echo '</section></div>
    </div>';
}
function lfb_add_form_setting() {
$nonce = wp_create_nonce( '_nonce_verify' );

        $create_url ="admin.php?page=add-new-form&action=edit&redirect=create&_wpnonce=".$nonce;

    echo "<div class='wrap'>
        <h1>Lead Form Settings</h1>
        <form method='post' action='".$create_url."' id='new_lead_form'>
            <div id='poststuff'>
                <div id='post-body'>
                    <div id='post-body-content'>
                        <div id='titlediv'>
                            <div id='titlewrap'>
                                <input type='text' class='new_form_heading' name='post_title' placeholder='Enter title here' value='' size='30' id='title' spellcheck='true' autocomplete='off'></div><!-- #titlewrap -->
                                <input type='hidden' name = '_wpnonce' value='".$nonce."' />
                            <div class='inside'>
                            </div>
                        </div><!-- #titlediv -->
                    </div><!-- #post-body-content -->
                </div>
            </div>";
            $this->lfb_basic_form();
            $this->lfb_form_first_fields();
            echo "<div id='append_new_field'></div>";
            $this->lfb_form_last_fields();
            echo "</table>
    </div>
    <p class='submit'><input type='submit' class='save_form button-primary' name='save_form' id='save_form' value='Save Form'> ".LFB_FORM_PRO_FIELD_TYPE."</p></td>
    </form><div id='message-box-error' class='message-box-error' ></div>
    </div>";
}

function lfb_basic_form() {
    echo "<div class='inside spth_setting_section'  id='wpth_add_form'>
          <h2 class='sec_head'>".esc_html__('Form Fields','lead-form-builder')."</h2>
          <table class='widefat' id='sortable'>
          <thead>
          <tr>
          <th>".esc_html__('Field name','lead-form-builder')." </th>
          <th>".esc_html__('Field Type','lead-form-builder')." </th>
          <th>".esc_html__('Default Value','lead-form-builder')." </th>
          <th>".esc_html__('Use Default Value as Placeholder','lead-form-builder')."</th>
          <th>".esc_html__('Required','lead-form-builder')." </th>
          <th>".esc_html__('Action','lead-form-builder')." </th>
          </tr></thead>";
}

function lfb_form_first_fields() {
    echo "<tbody class='append_new' ><tr id='form_field_row_1'>
	      <td><input type='text' name='form_field_1[field_name]' id='field_name_1' value=''></td>
		  <td>
		  <select class='form_field_select' name='form_field_1[field_type][type]' id='field_type_1'>
		    <option value='select'>".esc_html__('Select Field Type','lead-form-builder')."</option>
		    <option value='name'>".esc_html__('Name','lead-form-builder')."</option>		    
		    <option value='email'>".esc_html__('Email','lead-form-builder')."</option>
		    <option value='message'>".esc_html__('Message','lead-form-builder')."</option>
            <option value='dob'>".esc_html__('DOB','lead-form-builder')."</option>
		    <option value='date'>".esc_html__('Date','lead-form-builder')." </option>	    
		    <option value='text'>".esc_html__('Text (Single Line Text)','lead-form-builder')."</option>
        <option value='textarea'>".esc_html__('Textarea (Multiple Line Text)','lead-form-builder')." </option>
		    <option value='htmlfield'>".esc_html__('Content Area (Read only Text)','lead-form-builder')."</option>
            <option value='url'>".esc_html__('Url (Website url)','lead-form-builder')."</option>
            <option value='number'>".esc_html__('Number (Only Numeric 0-9 )','lead-form-builder')." </option>
            <option value='radio'>".esc_html__('Radio (Choose Single Option)','lead-form-builder')."</option>    
            <option value='option'>".esc_html__('Option (Choose Single Option)','lead-form-builder')."</option>  
            <option value='checkbox'>".esc_html__('Checkbox (Choose Multiple Option)','lead-form-builder')."</option>
            <option value='terms'>".esc_html__('Checkbox (Terms & condition)','lead-form-builder')." </option>
			</select>
			<div class='add_radio_checkbox_1' id='add_radio_checkbox'>
			<div class='' id='add_radio'></div>
			<div class='' id='add_checkbox'></div>
			<div class='' id='add_option'></div>
			</div>
		</td>
		<td><input type='text' class='default_value' name='form_field_1[default_value]' id='default_value_1' value=''>
    <div class='default_htmlfield_1' id='default_htmlfield'></div>
		<div class='add_default_radio_checkbox_1' id='add_default_radio_checkbox'>
			<div class='' id='default_add_radio'></div>
			<div class='' id='default_add_checkbox'></div>
			<div class='' id='default_add_option'></div>
		</div>
    <div class='default_terms_1' id='default_terms'></div>
		</td>
		<td><input type='checkbox' class='default_placeholder' name='form_field_1[default_placeholder]' id='default_placeholder_1' value='1'>
		</td>
		<td><input type='checkbox' name='form_field_1[is_required]' id='is_required_1' value='1'>
		</td>
		<td id='wpth_add_form_table_1'>
        <input type='hidden' value='1' name='form_field_1[field_id]'>
		</td>
		</tr></tbody>";
}

function lfb_form_last_fields(){

       echo "<tr id='form_field_row_0'><td></td>
          <td>
          <input type='hidden' name='form_field_0[field_name]' id='field_name_0' value='submit'>
          <select class='form_field_select' name='form_field_0[field_type][type]' id='field_type_0'>        
            <option value='submit'>".esc_html__('Submit Button','lead-form-builder')." </option>
            </select>
        </td>
        <td><input type='text' class='default_value' name='form_field_0[default_value]' id='default_value_0' value='SUBMIT'>
        </td>
        <td><input type='hidden' class='default_placeholder' name='form_field_0[default_placeholder]' id='default_placeholder_0' value='0'>
        </td>
        <td><input type='hidden' name='form_field_0[is_required]' checked id='is_required_0' value='1'>
        <input type='hidden' value='0' name='form_field_0[field_id]'>
        </td>
        </td>
        <td class='add-field'><span><input type='button' class='button lf_addnew' name='add_new' id='add_new_1' onclick='add_new_form_fields(1)' value='".esc_html__('Add New')."'></span>
        </td>
        </tr>";
}

}