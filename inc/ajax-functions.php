<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// File Upload functions
function lfb_upload_dir( $dirs ) {
    $dirs['subdir'] = '/lfb_uploads';
    $dirs['path'] = $dirs['basedir'] . '/lfb_uploads';
    $dirs['url'] = $dirs['baseurl'] . '/lfb_uploads';
    return $dirs;
}

function lfb_fileupload(){
add_filter( 'upload_dir', 'lfb_upload_dir' );
    $fileErrors = array(
        0 => __("There is no error, the file uploaded with success","lead-form-builder"),
        1 => __("The uploaded file exceeds the upload_max_files in server settings","lead-form-builder"),
        2 => __("The uploaded file exceeds the MAX_FILE_SIZE from html form","lead-form-builder"),
        3 => __("The uploaded file uploaded only partially","lead-form-builder"),
        4 => __("No file was uploaded","lead-form-builder"),
        6 => __("Missing a temporary folder","lead-form-builder"),
        7 => __("Failed to write file to disk","lead-form-builder"),
        8 => __("A PHP extension stoped file to upload","lead-form-builder") 
    );

    $file_data = isset( $_FILES ) ? $_FILES : array();
    $overrides = array( 'test_form' => false );
    $response = array();

foreach($file_data as $key => $file){
$uploaded_file = wp_handle_upload( $file, $overrides);

    if( $uploaded_file && ! isset( $uploaded_file['error'] ) ) {
        $response[$key]['response'] = __('SUCCESS','lead-form-builder');
        $response[$key]['filename'] = basename( $uploaded_file['url'] );
        $response[$key]['url'] = $uploaded_file['url'];
        $response[$key]['type'] = $uploaded_file['type'];
    } else {
        $response[$key]['response'] = "ERROR";
        $response[$key]['error'] = $uploaded_file['error'];
    }
}

 $parse = http_build_query($response);

print_r($parse);
remove_filter( 'upload_dir', 'lfb_upload_dir' );
die();
}
add_action('wp_ajax_fileupload', 'lfb_fileupload');
add_action('wp_ajax_nopriv_fileupload', 'lfb_fileupload');
/*
 * Save Lead collecting method
 */
function lfb_save_lead_settings() {
    $data_recieve_method = intval($_POST['data-recieve-method']);
    $this_form_id = intval($_POST['action-lead-setting']);
    global $wpdb;
    $table_name = LFB_FORM_FIELD_TBL;
    $update_query = "update " . LFB_FORM_FIELD_TBL . " set storeType='" . esc_sql($data_recieve_method) . "' where id='" . esc_sql($this_form_id) . "'";
    $th_save_db = new LFB_SAVE_DB($wpdb);
    $update_leads = $th_save_db->lfb_update_form_data($update_query);
    if ($update_leads) {
        echo esc_html('updated');
    }

    die();
}

add_action('wp_ajax_SaveLeadSettings', 'lfb_save_lead_settings');

/*
 * Save Email Settings
 */

function lfb_save_email_settings() {
    unset($_POST['action']);
    $this_form_id = intval($_POST['email_setting']['form-id']);
    global $wpdb;
    $table_name = LFB_FORM_FIELD_TBL;
    $update_query = "update " . LFB_FORM_FIELD_TBL . " set mail_setting='" . esc_sql($_POST) . "' where id='" . esc_sql($this_form_id) . "'";
    $th_save_db = new LFB_SAVE_DB($wpdb);
    $update_leads = $th_save_db->lfb_update_form_data($update_query);
    if ($update_leads) {
        echo esc_html('updated');
    }
    die();
}

add_action('wp_ajax_SaveEmailSettings', 'lfb_save_email_settings');
/*
 * Save Form Skin
 */

function lfb_savesuccessmsg() {
    global $wpdb;
    if(isset($_POST['lfb-form-id'])){
    unset($_POST['action']);
    $this_form_id = intval($_POST['lfb-form-id']);
    $table_name = LFB_FORM_FIELD_TBL;
    $update_query = "update " . LFB_FORM_FIELD_TBL . " set multiData='" . esc_sql($_POST) . "' where id='" . esc_sql($this_form_id) . "'";
    $th_save_db = new LFB_SAVE_DB($wpdb);
    $update_leads = $th_save_db->lfb_update_form_data($update_query);
    if ($update_leads) {
        echo esc_html('updated');
    }
    }
    die();
}

add_action('wp_ajax_lfbsavesuccessmsg', 'lfb_savesuccessmsg');

/*
 * Save captcha Keys
 */

function lfb_save_captcha_settings() {
$captcha_setting_sitekey = esc_attr($_POST['captcha-setting-sitekey']);
$captcha_setting_secret = esc_attr($_POST['captcha-setting-secret']);

if ( get_option('captcha-setting-sitekey') !== false ) {
    update_option('captcha-setting-sitekey', $captcha_setting_sitekey);
    update_option('captcha-setting-secret', $captcha_setting_secret);
} else {
    add_option('captcha-setting-sitekey', $captcha_setting_sitekey);
    add_option('captcha-setting-secret', $captcha_setting_secret);
}
    die();
}

add_action('wp_ajax_SaveCaptchaSettings', 'lfb_save_captcha_settings');

/*
 * Delete Leads From Back-end
 */
function lfb_delete_leads_backend() {
$user = get_userdata( get_current_user_id() );

$user = wp_get_current_user();
$allowed_roles = array( 'editor', 'administrator', 'lfb_role' );
 $nonce = $_REQUEST['_lfbnonce'];
// Get all the user roles as an array.

    $check = false;
if (isset($_POST['lead_id'])  && array_intersect( $allowed_roles, $user->roles ) && wp_verify_nonce( $nonce, 'lfb-nonce-rm' )) {
        $check = true;

        $this_lead_id = intval($_POST['lead_id']);
        global $wpdb;
        $table_name = LFB_FORM_DATA_TBL;

        $update_query = $wpdb->prepare(" DELETE FROM $table_name WHERE id = %d ", $this_lead_id);

        $th_save_db = new LFB_SAVE_DB($wpdb);
        $update_leads = $th_save_db->lfb_delete_form($update_query);
        echo esc_html($update_leads);
    }

    echo $check;
}

add_action('wp_ajax_delete_leads_backend', 'lfb_delete_leads_backend');

/*
 * Save captcha status for form ON/OFF
 */

function lfb_save_captcha_option() {
    $captcha_option = sanitize_text_field($_POST['captcha-on-off-setting']);
    $this_form_id = intval($_POST['captcha_on_off_form_id']);
    global $wpdb;
    $table_name = LFB_FORM_FIELD_TBL;
    $update_query = "update " . LFB_FORM_FIELD_TBL . " set captcha_status='" . esc_sql($captcha_option) . "' where id='" . esc_sql($this_form_id) . "'";
    $th_save_db = new LFB_SAVE_DB($wpdb);
    $update_leads = $th_save_db->lfb_update_form_data($update_query);
    if ($update_leads) {
        echo esc_html("updated");
    }
    die();
}

add_action('wp_ajax_SaveCaptchaOption', 'lfb_save_captcha_option');

/*
 * Show all Leads column on Lead Page Based on form selection
 */

function lfb_ShowAllLeadThisForm() {
    if ((isset($_POST['form_id']) && ($_POST['form_id'] != '')) || (isset($_GET['form_id']) && ($_GET['form_id'] != ''))) {

        global $wpdb;
        $table_name = LFB_FORM_DATA_TBL;
        $th_save_db = new LFB_SAVE_DB($wpdb);
        $nonce = wp_create_nonce( 'lfb-nonce-rm' );
        $start = 0;
        $limit = 10;
        $detail_view  = '';
        $slectleads =false;

        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $start = ($id - 1) * $limit;
            $form_id = intval($_GET['form_id']);
            $sn_counter = $start;
        } else {
            $id = 1;
            $form_id = intval($_POST['form_id']);
            $sn_counter = 0;
        }
        if (isset($_GET['detailview'])) {
            $detail_view = sanitize_text_field($_GET['detailview']);
        }

        if(isset($_POST['slectleads'])){
            $slectleads = sanitize_text_field($_POST['slectleads']);
        }

                $getArray = $th_save_db->lfb_get_all_view_leads_db($form_id,$start);
                $posts          = $getArray['posts'];
                $rows           = $getArray['rows'];
                $limit          = $getArray['limit'];
                $fieldData       = $getArray['fieldId'];
                $tableHead  = '';
                $headcount = 1;
                $leadscount = 5;

             foreach ($fieldData as $fieldkey => $fieldvalue) {
                // Html Field removed
                $pos = strpos($fieldkey, 'htmlfield_');
                if ($pos !== false) {
                    continue;
                }
                
           if($headcount < 6 && $slectleads){
            $tableHead  .='<th>' . $fieldvalue . '</th>';
            }elseif(!$slectleads){

            $tableHead  .='<th>' . $fieldvalue . '</th>';

            $leadscount =  $headcount;           
            }
            $fieldIdNew[] = $fieldkey;
            $headcount++;

           // } else{ break; }
            }

        if (!empty($posts)) {
            $entry_counter = 0;
            $table_body = '';
            $table_head = '';
            $popupTab   = '';

            if($headcount >= 6 && $leadscount == 5){
                     $table_head .='<th></th><th> . . . </th><th><input type="button" onclick="show_all_leads(' . $id . ',' . $form_id . ')" value="Show all Columns"></th>';
                }
            
        foreach ($posts as $results) {
            $table_row = '';
           // $table_head = '';
            $sn_counter++;
            $row_size_limit = 0;
            $form_data = $results->form_data;
            $lead_id = $results->id;
            $lead_date = date("jS F Y", strtotime($results->date));
            $form_data = maybe_unserialize($form_data);
            unset($form_data['hidden_field']);
            unset($form_data['action']);
            $entry_counter++;
            $complete_data = '';
            $popup_data_val = '';
             $date_td = '<td><b>'.$lead_date.'</b></td>';


            $returnData = $th_save_db->lfb_lead_form_value($form_data,$fieldIdNew,$fieldData,$leadscount);
                $table_row .= $returnData['table_row'];
                $table_row .= $date_td;

                foreach ($form_data as $form_data_key => $form_data_value) {
                    $row_size_limit++;

                    if (($detail_view != 1) && ($row_size_limit == 6) && $leadscount == 5) {
                        $table_row .='<td>. . .</td><td><a href="#lf-openModal-' . $lead_id . '" value="view">view</a></td>';
                    }
                }

                $complete_data .="<table><tr><th>Field</th><th>Value</th></tr>".$returnData['table_popup']."</table>";

                           /****/
                 $popupTab .= '<div id="lf-openModal-'.$lead_id.'" class="lf-modalDialog">
                          <div class="lfb-popup-leads" ><a href="#lf-close" title="Close" class="lf-close">X</a>'.$complete_data.'
                          </div>
                          </div>';

              //  $complete_data .=$returnData['table_popup']."</table>";

                $table_body .= '<tbody id="lead-id-' . $lead_id . '">';
               
               $table_body  .='<tr><td><span class="lead-count">' . $sn_counter . '</span><a class="lead-remove" onclick="delete_this_lead(' . $lead_id . ',\''.$nonce.'\')" ><i class="fa fa-trash" aria-hidden="true"></i></a></td>' . $table_row .'</tr>';
            }

            $thHead = '<div class="wrap" id="form-leads-show"><table class="show-leads-table wp-list-table widefat fixed" id="show-leads-table" ><thead><tr><th>Action</th>'.$tableHead.'<th>Date</th>'.$table_head.'</tr></thead>';  

            echo wp_kses_post($thHead. $table_body.'</tbody></table>'.$popupTab);

            $total = ceil($rows / $limit);
            if($headcount >= 6 && $leadscount == 5){

             if ($id > 1) {
                echo "<a href=''  onclick='lead_pagi_view(" . intval($id - 1) . "," . intval($form_id) . ")' class='button'><i class='fa fa-chevron-right'></i></a>";
            }
            if ($id != $total) {
                echo "<a href='' onclick='lead_pagi_view(" . intval($id + 1) . "," . intval($form_id) . ")' class='button'><i class='fa fa-chevron-left'></i></a>";
            }
            echo "<ul class='page'>";
            for ($i = 1; $i <= $total; $i++) {
                if ($i == $id) {
                    echo "<li class='lf-current'><a href='#'>" . intval($i) . "</a></li>";
                } else {
                    echo "<li><a href='' onclick='lead_pagi_view(" . intval($i) . "," . intval($form_id) . ")'>" . $i . "</a></li>";
                }
            }
            echo '</ul>';

} else {

      if ($id > 1) {
                echo "<a href=''  onclick='lead_pagination(" . intval($id - 1) . "," . intval($form_id) . ")' class='button'><i class='fa fa-chevron-right'></i></a>";
            }
            if ($id != $total) {
                echo "<a href='' onclick='lead_pagination(" . intval($id + 1) . "," . intval($form_id) . ")' class='button'><i class='fa fa-chevron-left'></i></a>";
            }
            echo "<ul class='page'>";
            for ($i = 1; $i <= $total; $i++) {
                if ($i == $id) {
                    echo "<li class='lf-current'><a href='#'>" . intval($i) . "</a></li>";
                } else {
                    echo "<li><a href='' onclick='lead_pagination(" . intval($i) . "," . intval($form_id) . ")'>" . intval($i) . "</a></li>";
                }
            }
            echo '</ul>';
}

        } else {
            echo esc_html("Opps No lead...!!");
        }
        die();
    }
}

add_action('wp_ajax_ShowAllLeadThisForm', 'lfb_ShowAllLeadThisForm');



function lfb_ShowLeadPagi() {
    if ((isset($_POST['form_id']) && ($_POST['form_id'] != '')) || (isset($_GET['form_id']) && ($_GET['form_id'] != ''))) {
        global $wpdb;
        $table_name = LFB_FORM_DATA_TBL;
        $th_save_db = new LFB_SAVE_DB($wpdb);
        $nonce = wp_create_nonce( 'lfb-nonce-rm' );
        $start = 0;
        $limit = 10;
        $detail_view = '';

        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $start = ($id - 1) * $limit;
            $form_id = intval($_GET['form_id']);
            $sn_counter = $start;
        } else {
            $id = 1;
            $form_id = intval($_POST['form_id']);
            $sn_counter = 0;
        }
        if (isset($_GET['detailview'])) {
            $detail_view = isset($_GET['detailview']);
        }

                $getArray = $th_save_db->lfb_get_all_view_leads_db($form_id,$start);
                $posts          = $getArray['posts'];
                $rows           = $getArray['rows'];
                $limit          = $getArray['limit'];
                $fieldData       = $getArray['fieldId'];
                $tableHead  = '';
                $headcount = 1;

             foreach ($fieldData as $fieldkey => $fieldvalue) {
           if($headcount < 6){
            $tableHead  .='<th>' . $fieldvalue . '</th>';
            }
            $fieldIdNew[] = $fieldkey;
           // } else{ break; }
            $headcount++;
            }
            if (!empty($posts)) {
            $entry_counter = 0;
            $table_body = '';
            $table_head = '';
            $popupTab   = '';

             if($headcount >= 6){
                     $table_head .='<th> . . . </th><th><input type="button" onclick="show_all_leads(' . $id . ',' . $form_id . ')" value="Show all Columns"></th>';
                }

        foreach ($posts as $results) {
            $table_row = '';
            $sn_counter++;
            $row_size_limit = 0;
            $form_data = $results->form_data;
            $lead_id = $results->id;
            $form_data = maybe_unserialize($form_data);
            unset($form_data['hidden_field']);
            unset($form_data['action']);
            $entry_counter++;
            $complete_data = '';
            $popup_data_val = '';

            $returnData = $th_save_db->lfb_lead_form_value($form_data,$fieldIdNew,$fieldData,5);
                $table_row .= $returnData['table_row'];

                foreach ($form_data as $form_data_key => $form_data_value) {
                    $row_size_limit++;

                    if (($detail_view != 1) && ($row_size_limit == 6)) {
                        $table_row .= '<td>. . .</td><td><a href="#lf-openModal-' . $lead_id . '" value="view">view</a></td>';
                    }
                }

                $complete_data .= "<table><tr><th>Field</th><th>Value</th></tr>".$returnData['table_popup']."</table>";

                           /****/
                 $popupTab .= '<div id="lf-openModal-'.$lead_id.'" class="lf-modalDialog">
                          <div class="lfb-popup-leads" ><a href="#lf-close" title="Close" class="lf-close">X</a>'.$complete_data.'
                          </div>
                          </div>';
                          /****/
                $table_body .= '<tbody id="lead-id-' . $lead_id . '">';
               
               $table_body  .='<tr><td><span class="lead-count">' . $sn_counter . '</span><a class="lead-remove" onclick="delete_this_lead(' . $lead_id . ',\''.$nonce.'\')" ><i class="fa fa-trash" aria-hidden="true"></i></a></td>' . $table_row . '</tr>';
            }

            $thHead = '<div class="wrap" id="form-leads-show"><table class="show-leads-table wp-list-table widefat fixed" id="show-leads-table" ><thead><tr><th>Action</th>'.$tableHead.$table_head.'</tr></thead>';  

            echo wp_kses_post($thHead. $table_body.'</tbody></table>'.$popupTab);

            $total = ceil($rows / $limit);
            if ($id > 1) {
                echo "<a href=''  onclick='lead_pagi_view(" . intval($id - 1) . "," . intval($form_id) . ")' class='button'><i class='fa fa-chevron-right'></i></a>";
            }
            if ($id != $total) {
                echo "<a href='' onclick='lead_pagi_view(" . intval($id + 1) . "," . intval($form_id) . ")' class='button'><i class='fa fa-chevron-left'></i></a>";
            }
            echo "<ul class='page'>";
            for ($i = 1; $i <= $total; $i++) {
                if ($i == $id) {
                    echo "<li class='lf-current'><a href='#'>" . intval($i) . "</a></li>";
                } else {
                    echo "<li><a href='' onclick='lead_pagi_view(" . intval($i) . "," . intval($form_id) . ")'>" . intval($i) . "</a></li>";
                }
            }
            echo '</ul>';
        } else {
            echo esc_html("Opps No lead...!!");
        }
        die();
    }
}
add_action('wp_ajax_ShowLeadPagi', 'lfb_ShowLeadPagi');

/*
 * Show Leads on Lead Page Based on form selection
 */

function lfb_ShowAllLeadThisFormDate() {
    if ((isset($_POST['form_id']) && ($_POST['form_id'] != '')) || (isset($_GET['form_id']) && ($_GET['form_id'] != ''))) {
        global $wpdb;
        $nonce = wp_create_nonce( 'lfb-nonce-rm' );
        $table_name = LFB_FORM_DATA_TBL;
        $th_save_db = new LFB_SAVE_DB($wpdb);
        $start = 0;
        $limit = 10;
        $detail_view = '';

        if (isset($_GET['id'])) {
            $id = intval($_GET['id']);
            $datewise = esc_html($_GET['datewise']);
            $start = ($id - 1) * $limit;
            $form_id = intval($_GET['form_id']);
            $sn_counter = $start;
        } else {
            $id = 1;
            $datewise ='';
            $sn_counter = 0;
        }
        if (isset($_GET['detailview'])) {
            $detail_view = sanitize_text_field($_GET['detailview']);
        }
        $getArray =  $th_save_db->lfb_get_all_view_date_leads_db($form_id,$datewise,$start);

        $posts          = $getArray['posts'];
        $rows           = $getArray['rows'];
        $limit          = $getArray['limit'];
        $fieldData       = $getArray['fieldId'];
        $fieldIdNew     = array();
        $headcount = 1;

            $tableHead  = '';


            foreach ($fieldData as $fieldkey => $fieldvalue) {
           if($headcount < 6){
            $tableHead  .='<th>' . $fieldvalue . '</th>';
            }
            $fieldIdNew[] = $fieldkey;
           // } else{ break; }
            $headcount++;
            }

        if (!empty($posts)) {
            $entry_counter = 0;
            $value1 = 0;
            $table_body = '';
            $table_head = '';
            $popupTab   = '';


            if($headcount >= 6){
                     $table_head .='<th><input type="button" onclick="show_all_leads(' . $id . ',' . $form_id . ')" value="Show all fields"></th>';
                }

            foreach ($posts as $results) {
                $table_row = '';
                $sn_counter++;
                $row_size_limit = 0;
                $form_data = $results->form_data;
                $lead_id = $results->id;
                $form_data = maybe_unserialize($form_data);
                unset($form_data['hidden_field']);
                unset($form_data['action']);
                $entry_counter++;
                $complete_data = '';
                $popup_data_val = '';

                    $returnData = $th_save_db->lfb_lead_form_value($form_data,$fieldIdNew,$fieldData,5);
                $table_row .= $returnData['table_row'];



                foreach ($form_data as $form_data_key => $form_data_value) {
                    $row_size_limit++;

                    if (($detail_view != 1) && ($row_size_limit == 6)) {
                        $table_row .= '<td>. . . . .</td><td><a href="#lf-openModal-' . $lead_id . '" value="view">view</a></td>';
                    }
                }

                $complete_data .= "<table><tr><th>Field</th><th>Value</th></tr>". $returnData['table_popup']."</table>";

                           /****/
                 $popupTab .= '<div id="lf-openModal-'.$lead_id.'" class="lf-modalDialog">
                          <div class="lfb-popup-leads" ><a href="#lf-close" title="Close" class="lf-close">X</a>'.$complete_data.'
                          </div>
                          </div>';
                          /****/
                $table_body .= '<tbody id="lead-id-' . $lead_id . '">';
               
               $table_body  .='<tr><td><span class="lead-count">' . $sn_counter . '</span><a class="lead-remove" onclick="delete_this_lead(' . $lead_id . ',\''.$nonce.'\')" ><i class="fa fa-trash" aria-hidden="true"></i></a></td>' . $table_row . '</tr>';
            }

            $thHead = '<div class="wrap" id="form-leads-show"><table class="show-leads-table wp-list-table widefat fixed" id="show-leads-table" ><thead><tr><th>Action</th>'.$tableHead.$table_head.'</tr></thead>';  

            echo wp_kses_post($thHead. $table_body.'</tbody></table>'.$popupTab);

            $rows = count($rows);
            $total = ceil($rows / $limit);
            if ($id > 1) {
                echo "<a href=''  onclick='lead_pagination_datewise(" . intval($id - 1) . "," . intval($form_id) . ",\"".$datewise."\");' class='button'><i class='fa fa-chevron-right'></i></a>";
            }
            if ($id != $total) {
                echo "<a href='' onclick='lead_pagination_datewise(" . intval($id + 1) . "," . intval($form_id) . ",\"".$datewise."\");' class='button'><i class='fa fa-chevron-left'></i></a>";
            }
            echo "<ul class='page'>";
            for ($i = 1; $i <= $total; $i++) {
                if ($i == $id) {
                    echo "<li class='lf-current'><a href='#'>" . $i . "</a></li>";
                } else {
                    echo "<li><a href='' onclick='lead_pagination_datewise(".intval($i).",".intval($form_id).",\"".$datewise."\");'>" . $i . "</a></li>";
                }
            }
            echo '</ul>';
        } else {
            echo esc_html("Opps No lead...!!");
        }
        die();
    }
}
add_action('wp_ajax_ShowAllLeadThisFormDate', 'lfb_ShowAllLeadThisFormDate');

/*
 * Save from Data from front-end
 */

    function lfb_form_name_email_filter($form_data){
        $name_email = array();
        $e = false;
        $n = false;
        foreach($form_data as $key => $value){
             $email = strpos($key,'email_');
             $name = strpos($key, 'name_');
            if($email !== false) {
                $name_email['email'] = $value;
                $e = true;
            }elseif($name !== false){
                $name_email['name'] = $value;
                $n = true;
            }
            if ($e === true && $n === true) {
                break;
            }

            }
            return $name_email;
    }

    function lfb_lead_sanitize($leads){
        if(is_array($leads)){
            foreach($leads as $key=>$value){
                $rKey = preg_replace("/[^a-zA-Z]+/", "", $key);
                    if($rKey === 'name' || $rKey === 'text' || $rKey === 'radio' || $rKey === 'option'){
                        $leads[$key] = sanitize_text_field($value);
                                
                    }elseif($rKey === 'email'){
                    $leads[$key] = sanitize_email($value);

                    }elseif($rKey === 'number'){
                        $leads[$key] = intval($value);

                    }elseif($rKey === 'message' || $rKey === 'textarea'){
                    $leads[$key] = sanitize_textarea_field($value);

                    }elseif($rKey === 'date' || $rKey === 'dob'){
                             $leads[$key] = sanitize_text_field($value);
                        
                    }elseif($rKey === 'url'){
                        $leads[$key] = esc_url_raw($value);
                    }elseif($rKey === 'checkbox'){

                                    foreach($value as $ckey=>$cvalue){
                                            $value[$ckey] = sanitize_text_field($cvalue);
                                    }
                            $leads[$key] = $value;
                    }

            } // end foreach

              return $leads;

        }

    }

function lfb_Save_Form_Data() {
    $form_id = intval($_POST['hidden_field']);
    unset($_POST['g-recaptcha-response']);
    unset($_POST['action']);
    unset($_POST['hidden_field']);
    
    $en = lfb_form_name_email_filter($_POST);

    if((isset($en['email']))&&($en['email']!='')){
    $user_emailid =sanitize_email($en['email']);
    }else{
    $user_emailid ='invalid_email';
    }          
    $sanitize_leads =  lfb_lead_sanitize($_POST);
    $form_data = maybe_serialize($sanitize_leads);

    $lf_store   = new LFB_LeadStoreType();
    $th_save_db = new LFB_SAVE_DB();

    $lf_store->lfb_mail_type($form_id,$form_data,$th_save_db,$user_emailid);
    
    die();
}

add_action('wp_ajax_Save_Form_Data', 'lfb_Save_Form_Data');
add_action('wp_ajax_nopriv_Save_Form_Data', 'lfb_Save_Form_Data');

function lfb_verifyFormCaptcha() {
if ((isset($_POST['captcha_res'])) && (!empty($_POST['captcha_res']))) {
        $captcha = stripslashes($_POST['captcha_res']);
        $secret_key = get_option('captcha-setting-secret');
$response = wp_remote_post(
  'https://www.google.com/recaptcha/api/siteverify',
  array(
    'method' => 'POST',
    'body' => array(
      'secret' => $secret_key,
      'response' => $captcha
    )
  )
);
$reply_obj = json_decode( wp_remote_retrieve_body( $response ) );
       if(isset($reply_obj->success) && $reply_obj->success==1){
         esc_html_e('Yes','lead-form-builder');
        }
        else{
         esc_html_e('No','lead-form-builder');
        }
    }else{
         esc_html_e('Invalid','lead-form-builder');
    }
    die();
    }
add_action('wp_ajax_verifyFormCaptcha', 'lfb_verifyFormCaptcha');
add_action('wp_ajax_nopriv_verifyFormCaptcha', 'lfb_verifyFormCaptcha');

function lfb_RememberMeThisForm(){
if ((isset($_POST['form_id'])) && (!empty($_POST['form_id']))) {

    $remember_me = intval($_POST['form_id']);
    if (get_option('lf-remember-me-show-lead') !== false ) {
    update_option('lf-remember-me-show-lead',$remember_me);
    }else{
    add_option('lf-remember-me-show-lead',$remember_me);
    }
    echo esc_html(get_option('lf-remember-me-show-lead'));
    die();
}
}
add_action('wp_ajax_RememberMeThisForm', 'lfb_RememberMeThisForm');


/*
 * Save Email Settings
 */

function lfb_SaveUserEmailSettings() {
    unset($_POST['action']);
    $email_setting = maybe_serialize($_POST);
    $this_form_id = intval($_POST['user_email_setting']['form-id']);
    global $wpdb;
    $table_name = LFB_FORM_FIELD_TBL;
    $update_query = "update " . LFB_FORM_FIELD_TBL . " set usermail_setting='" . esc_sql($email_setting) . "' where id='" . esc_sql($this_form_id) . "'";
    $th_save_db = new LFB_SAVE_DB($wpdb);
    $update_leads = $th_save_db->lfb_update_form_data($update_query);
    if ($update_leads) {
        echo esc_html("updated");
    }
    die();
}
add_action('wp_ajax_SaveUserEmailSettings', 'lfb_SaveUserEmailSettings');

/*
 * Save captcha status for form ON/OFF
 */

function lfb_save_extension_onoff() {

   if(isset($_POST['extension_onoff_value'])):
        $esxtname = intval($_POST['ext_name']);
        $extonoff = ($_POST['extension_onoff_value']=='ON')?0:1;
        $formid   = intval($_POST['extension_on_off_form_id']);
        $lfbDb = new LFB_SAVE_DB();
        $lfbDb->lfb_mcpi_update_onoff($formid,$esxtname,$extonoff);
    endif;

    die();
}
add_action('wp_ajax_SaveExtensionOption', 'lfb_save_extension_onoff');
/*
 * Save captcha status for form ON/OFF
 */

function lfb_save_colors_settings() {

    if(isset($_POST['colorid'])):
    $lfbDb = new LFB_SAVE_DB();
        $fid = intval($_POST['colorid']);
        unset($_POST['action']);
     $serialize = maybe_serialize(array_map('stripslashes_deep', $_POST));
   echo $lfbDb->lfb_colors_insert_update($fid,$serialize);

  endif;
    die();
}
add_action('wp_ajax_SaveColorsSettings', 'lfb_save_colors_settings');
