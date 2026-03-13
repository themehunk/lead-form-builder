<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
Class LFB_Show_Leads {


function expanded_alowed_tags() {
    $allowed = wp_kses_allowed_html( 'post' );

    // form fields - input
    $allowed['a'] = array(
        'href'    => array(),
        'class'   => array(),
        'title'   => array(),
        'target'  => array(),
        'value'   => array(),
        'onclick' => array(),
    );
    $allowed['button'] = array(
        'type'    => array(),
        'class'   => array(),
        'id'      => array(),
        'onclick' => array(),
        'rem_nonce' => array(),
    );
// form fields - input
    $allowed['input'] = array(
        'class'   => array(),
        'id'      => array(),
        'name'    => array(),
        'value'   => array(),
        'type'    => array(),
        'onclick' => array(),
        'checked' => array(),
    );

    $allowed['label'] = array(
        'class' => array(),
        'for'   => array(),
    );

    $allowed['span'] = array(
        'class' => array(),
        'id'    => array(),
    );

        $allowed['option'] = array(
            'value'    => array(),
            'selected'   => array(),
        );

    return $allowed;
}



    function lfb_show_form_leads() {
        global $wpdb;
        $option_form = '';
        $first_form=0;
        $th_save_db = new LFB_SAVE_DB($wpdb);
        $table_name = LFB_FORM_FIELD_TBL;
        $prepare_16 = $wpdb->prepare("SELECT * FROM $table_name WHERE form_status = %s ORDER BY id DESC ",'ACTIVE');
        $posts = $th_save_db->lfb_get_form_content($prepare_16);
        if (!empty($posts)) {
            foreach ($posts as $results) {
                $first_form++;
                $form_title = $results->form_title;
                $form_id = $results->id;
                if($first_form==1){
                $first_form_id = $results->id;
                if (get_option('lf-remember-me-show-lead') !== false ) {
                $first_form_id = get_option('lf-remember-me-show-lead');
                }
                }
                $lead_count = intval( $th_save_db->lfb_post_count( $form_id ) );
                $option_form .= '<option ' . ($first_form_id == $form_id ? 'selected="selected"' : "" ) . ' value=' . intval($form_id) . '>' . esc_html($form_title) . ' (' . $lead_count . ')</option>';
            }
        }
        $rem_nonce = wp_create_nonce( 'rem-nonce' );

        lfb_admin_menu_header();

        echo '<div class="lfb-leads-filter-bar">
            <div class="lfb-leads-filter-left">
                ' . lfb_svg('filter') . '
                <label class="lfb-leads-filter-label" for="select_form_lead">' . esc_html__( 'Form', 'lead-form-builder' ) . '</label>
                <div class="lfb-leads-select-wrap">
                    <select name="select_form_lead" id="select_form_lead">' . wp_kses( $option_form, $this->expanded_alowed_tags() ) . '</select>
                    <span class="lfb-leads-select-arrow">' . lfb_svg('chevron-down') . '</span>
                </div>
            </div>
            <div class="lfb-leads-filter-right">
                <button type="button" class="lfb-leads-remember-btn" rem_nonce="' . $rem_nonce . '" id="remember_this_form_id" onclick="remember_this_form_id();">
                    <i class="fa fa-bookmark" aria-hidden="true"></i>
                    ' . esc_html__( 'Remember', 'lead-form-builder' ) . '
                </button>
                <span id="remember_this_message"></span>
            </div>
        </div>';

        echo '<div class="lfb-leads-bulk-bar">
            <div class="lfb-bulk-left">
                <span class="lfb-bulk-icon">' . lfb_svg( 'select-all' ) . '</span>
                <span class="lfb-selected-count"><b class="lfb-leads-sel-num">0</b> ' . esc_html__( 'leads selected', 'lead-form-builder' ) . '</span>
            </div>
            <div class="lfb-bulk-right">
                <button type="button" class="lfb-bulk-delete-btn lfb-leads-bulk-delete-btn">' . lfb_svg( 'trash' ) . ' ' . esc_html__( 'Delete', 'lead-form-builder' ) . '</button>
                <button type="button" class="lfb-bulk-cancel-btn lfb-leads-bulk-cancel-btn">' . lfb_svg( 'close', 14 ) . ' ' . esc_html__( 'Cancel', 'lead-form-builder' ) . '</button>
            </div>
        </div>
        <div class="lfb-leads-delete-modal-overlay" style="display:none;">
            <div class="lfb-delete-modal">
                <div class="lfb-modal-icon">' . lfb_svg( 'warning' ) . '</div>
                <h3>' . esc_html__( 'Delete Leads?', 'lead-form-builder' ) . '</h3>
                <p class="lfb-modal-msg">' . esc_html__( 'This will permanently remove the selected leads. This action cannot be undone.', 'lead-form-builder' ) . '</p>
                <div class="lfb-modal-actions">
                    <button type="button" class="lfb-leads-modal-cancel-btn lfb-modal-cancel-btn">' . esc_html__( 'Cancel', 'lead-form-builder' ) . '</button>
                    <button type="button" class="lfb-leads-modal-confirm-btn lfb-bulk-delete-btn">' . lfb_svg( 'trash' ) . ' ' . esc_html__( 'Yes, Delete', 'lead-form-builder' ) . '</button>
                </div>
            </div>
        </div>';

        echo '<div class="wrap lfb-leads-table-wrap" id="form-leads-show">';
        $this->lfb_show_leads_first_form($first_form_id);
        echo '</div>';
    }

function lfb_show_leads_first_form($form_id){

        $start = 0;

        $th_save_db = new LFB_SAVE_DB();
        $getArray =  $th_save_db->lfb_get_all_view_leads_db($form_id,$start);
        $nonce = wp_create_nonce( 'lfb-nonce-rm' );

        $posts          = $getArray['posts'];
        $rows           = $getArray['rows'];
        $limit          = $getArray['limit'];
        $fieldData       = $getArray['fieldId'];
        $sn_counter     = 0;
        $detail_view    = '';
        $id             = $headcount = 1;
        $fieldIdNew     = array();
        $tableHead  = '';
        
             foreach ($fieldData as $fieldkey => $fieldvalue) {
                // Html Field removed
                $pos = strpos($fieldkey, 'htmlfield_');
                if ($pos !== false) {
                    continue;
                }
                
           if($headcount < 6){
            $tableHead  .='<th>' . esc_html($fieldvalue) . '</th>';
            }
            $fieldIdNew[] = $fieldkey;
           // } else{ break; }
            $headcount++;
            }

        if (!empty($posts)) {
            $entry_counter = 0;
            $table_head = '';
            $table_body = '';
            $popupTab   = '';

            $table_head .= '<th><input type="button" onclick="show_all_leads(' . $id . ',' . $form_id . ')" value="' . esc_attr__( 'Show Details', 'lead-form-builder' ) . '"></th>';

            foreach ($posts as $results) {
                $table_row = '';
                $sn_counter++;
                $form_data = $results->form_data;
                $lead_id = $results->id;
                $form_data = maybe_unserialize($form_data);
                $lead_date = date("M d, Y", strtotime($results->date));
                unset($form_data['hidden_field']);
                unset($form_data['action']);
                unset($form_data['g-recaptcha-response']);
                $entry_counter++;
                $complete_data = '';
                $date_td = '<td><b>' . $lead_date . '</b></td>';

                $returnData = $th_save_db->lfb_lead_form_value($form_data,$fieldIdNew,$fieldData,5);
                $table_row .= $returnData['table_row'];
                $table_row .= $date_td;
                $table_row .= '<td><a href="#lf-openModal-' . $lead_id . '" value="view">view</a></td>';

                $popup_inner = wp_kses(
                    '<table><tr><th>' . esc_html__( 'Field', 'lead-form-builder' ) . '</th><th>' . esc_html__( 'Value', 'lead-form-builder' ) . '</th></tr>' . $returnData['table_popup'] . '<tr><td>' . esc_html__( 'Date', 'lead-form-builder' ) . '</td>' . $date_td . '</tr></table>',
                    $this->expanded_alowed_tags()
                );
                $popupTab .= '<div id="lf-openModal-' . $lead_id . '" class="lf-modalDialog">
                    <div class="lfb-popup-leads">
                        <div class="lfb-popup-header">
                            <span class="lfb-popup-title">' . lfb_svg( 'file', 16 ) . ' ' . esc_html__( 'Lead Details', 'lead-form-builder' ) . '</span>
                            <a href="#lf-close" title="Close" class="lf-close">' . lfb_svg( 'close', 14 ) . '</a>
                        </div>
                        <div class="lfb-popup-body">' . $popup_inner . '</div>
                    </div>
                </div>';

                $table_body .= '<tbody id="lead-id-' . $lead_id . '">';
                $table_body .= '<tr><td><label class="lfb-custom-cb"><input type="checkbox" class="lfb-lead-cb" value="' . $lead_id . '" /><span class="lfb-cb-mark"></span></label><a class="lead-remove" onclick="delete_this_lead(' . $lead_id . ',\'' . $nonce . '\')" ><i class="fa fa-trash" aria-hidden="true"></i></a></td>' . $table_row . '</tr>';
            }

            $thHead = '<div class="lfb-leads-content"><table class="show-leads-table wp-list-table widefat fixed" id="show-leads-table">
                <thead><tr><th><label class="lfb-custom-cb"><input type="checkbox" class="lfb-lead-select-all" /><span class="lfb-cb-mark"></span></label></th>' . $tableHead . '<th>' . esc_html__( 'Date', 'lead-form-builder' ) . '</th>' . $table_head . '</tr></thead>';

            echo wp_kses( $thHead . $table_body . '</tbody></table></div>', $this->expanded_alowed_tags() );
            echo wp_kses( $popupTab, $this->expanded_alowed_tags() );

            $total = ceil($rows / $limit);
            if ($id > 1) {
                echo "<a href=''  onclick='lead_pagi_view(" . intval($id - 1) . "," . intval($form_id) . ")' class='button'><i class='fa fa-chevron-left'></i></a>";
            }
            if ($id != $total) {
                echo "<a href='' onclick='lead_pagi_view(" . intval($id + 1) . "," . intval($form_id) . ")' class='button'><i class='fa fa-chevron-right'></i></a>";
            }
            ?> <ul class='page'>
                <?php
            for ($i = 1; $i <= $total; $i++) {
                if ($i == $id) {
                  ?> <li class='lf-current'><a href='#'><?php echo intval($i); ?></a></li> <?php
                } else {
                    echo "<li><a href='' onclick='lead_pagi_view(" . intval($i) . "," . intval($form_id) . ")'>" . intval($i) . "</a></li>";
                }
            }
             ?> </ul>
             <?php
        } else {
            echo '<div class="lfb-leads-content lfb-no-leads"><p>' . esc_html__( 'No leads..!', 'lead-form-builder' ) . '</p></div>';
        }
    }

// show all leads

    function lfb_show_form_leads_datewise($form_id,$leadtype){
        $th_save_db = new LFB_SAVE_DB();

        $getArray =  $th_save_db->lfb_get_all_view_date_leads_db($form_id,$leadtype);
        $nonce = wp_create_nonce( 'lfb-nonce-rm' );

        $posts          = $getArray['posts'];
        $rows           = $getArray['rows'];
        $limit          = $getArray['limit'];
        $fieldData       = $getArray['fieldId'];
        $sn_counter     = 0;
        $detail_view    = '';
        $id             = $headcount = 1;
        $fieldIdNew     = array();

            $tableHead  = '';
            foreach ($fieldData as $fieldkey => $fieldvalue) {
                // Html Field removed
                $pos = strpos($fieldkey, 'htmlfield_');
                if ($pos !== false) {
                    continue;
                }

           if($headcount < 6){
            $tableHead  .='<th>' . esc_html($fieldvalue) . '</th>';
            }
            $fieldIdNew[] = $fieldkey;
           // } else{ break; }
            $headcount++;
            }


        if (!empty($posts)) {
            $entry_counter = 0;
            $value1 = 0;
            $table_head = '';
            $table_body = '';
            $popupTab   = '';
           
            $table_head .= '<th><input type="button" onclick="show_all_leads(' . $id . ',' . $form_id . ')" value="' . esc_html__( 'Show Details', 'lead-form-builder' ) . '"></th>';
            foreach ($posts as $results) {
                $table_row = '';
                $form_data = $results->form_data;
                $lead_date = date("M d, Y", strtotime($results->date));
                $lead_id = $results->id;
                $form_data = maybe_unserialize($form_data);
                unset($form_data['hidden_field']);
                unset($form_data['action']);
                unset($form_data['g-recaptcha-response']);
                $entry_counter++;
                $sn_counter++;
                $complete_data = '';
                $date_td = '<td><b>' . $lead_date . '</b></td>';

                $returnData = $th_save_db->lfb_lead_form_value($form_data,$fieldIdNew,$fieldData,5);
                $table_row .= $returnData['table_row'];
                $table_row .= $date_td;
                $table_row .= '<td><a href="#lf-openModal-' . $lead_id . '" value="view">view</a></td>';

                $popup_inner = wp_kses(
                    '<table><tr><th>' . esc_html__( 'Field', 'lead-form-builder' ) . '</th><th>' . esc_html__( 'Value', 'lead-form-builder' ) . '</th></tr>' . $returnData['table_popup'] . '<tr><td>' . esc_html__( 'Date', 'lead-form-builder' ) . '</td>' . $date_td . '</tr></table>',
                    $this->expanded_alowed_tags()
                );
                $popupTab .= '<div id="lf-openModal-' . $lead_id . '" class="lf-modalDialog">
                    <div class="lfb-popup-leads">
                        <div class="lfb-popup-header">
                            <span class="lfb-popup-title">' . lfb_svg( 'file', 16 ) . ' ' . esc_html__( 'Lead Details', 'lead-form-builder' ) . '</span>
                            <a href="#lf-close" title="Close" class="lf-close">' . lfb_svg( 'close', 14 ) . '</a>
                        </div>
                        <div class="lfb-popup-body">' . $popup_inner . '</div>
                    </div>
                </div>';
                $table_body .= '<tbody id="lead-id-' . $lead_id . '">';
                $table_body .= '<tr><td><label class="lfb-custom-cb"><input type="checkbox" class="lfb-lead-cb" value="' . $lead_id . '" /><span class="lfb-cb-mark"></span></label><a class="lead-remove" onclick="delete_this_lead(' . $lead_id . ',\'' . $nonce . '\')" ><i class="fa fa-trash" aria-hidden="true"></i></a></td>' . $table_row . '</tr>';
            }

            echo '<div class="lfb-leads-bulk-bar">
                <div class="lfb-bulk-left">
                    <span class="lfb-bulk-icon">' . lfb_svg( 'select-all' ) . '</span>
                    <span class="lfb-selected-count"><b class="lfb-leads-sel-num">0</b> ' . esc_html__( 'leads selected', 'lead-form-builder' ) . '</span>
                </div>
                <div class="lfb-bulk-right">
                    <button type="button" class="lfb-bulk-delete-btn lfb-leads-bulk-delete-btn">' . lfb_svg( 'trash' ) . ' ' . esc_html__( 'Delete', 'lead-form-builder' ) . '</button>
                    <button type="button" class="lfb-bulk-cancel-btn lfb-leads-bulk-cancel-btn">' . lfb_svg( 'close', 14 ) . ' ' . esc_html__( 'Cancel', 'lead-form-builder' ) . '</button>
                </div>
            </div>
            <div class="lfb-leads-delete-modal-overlay" style="display:none;">
                <div class="lfb-delete-modal">
                    <div class="lfb-modal-icon">' . lfb_svg( 'warning' ) . '</div>
                    <h3>' . esc_html__( 'Delete Leads?', 'lead-form-builder' ) . '</h3>
                    <p class="lfb-modal-msg">' . esc_html__( 'This will permanently remove the selected leads. This action cannot be undone.', 'lead-form-builder' ) . '</p>
                    <div class="lfb-modal-actions">
                        <button type="button" class="lfb-leads-modal-cancel-btn lfb-modal-cancel-btn">' . esc_html__( 'Cancel', 'lead-form-builder' ) . '</button>
                        <button type="button" class="lfb-leads-modal-confirm-btn lfb-bulk-delete-btn">' . lfb_svg( 'trash' ) . ' ' . esc_html__( 'Yes, Delete', 'lead-form-builder' ) . '</button>
                    </div>
                </div>
            </div>';

            $thHead = '<div class="lfb-leads-content"><table class="show-leads-table wp-list-table widefat fixed" id="show-leads-table">
                <thead><tr><th><label class="lfb-custom-cb"><input type="checkbox" class="lfb-lead-select-all" /><span class="lfb-cb-mark"></span></label></th>' . $tableHead . '<th>' . esc_html__( 'Date', 'lead-form-builder' ) . '</th>' . $table_head . '</tr></thead>';

            echo wp_kses( $thHead . $table_body . '</tbody></table></div>', $this->expanded_alowed_tags() );
            echo wp_kses( $popupTab, $this->expanded_alowed_tags() );

            $rows = count($rows);
            $total = ceil($rows / $limit);
            if ($id > 1) {
                echo "<a href=''  onclick='lead_pagination_datewise(" . intval($id - 1) . "," . intval($form_id) . ",\"".esc_attr($leadtype)."\");' class='button'><i class='fa fa-chevron-left'></i></a>";
            }
            if ($id != $total) {
                echo "<a href='' onclick='lead_pagination_datewise(" . intval($id + 1) . "," . intval($form_id) . ",\"".esc_attr($leadtype)."\");' class='button'><i class='fa fa-chevron-right'></i></a>";
            }
            echo "<ul class='page'>";
            for ($i = 1; $i <= $total; $i++) {
                if ($i == $id) {
                    ?> <li class='lf-current'><a href='#'><?php echo intval($i); ?></a></li> <?php
                } else {
                    echo "<li><a href='' onclick='lead_pagination_datewise(".intval($i).",".intval($form_id).",\"".esc_attr($leadtype)."\");'>" . intval($i) . "</a></li>";
                }
            }
             ?></ul>
             <?php
        } else {
            echo '<div class="lfb-leads-content lfb-no-leads"><p>' . esc_html__( 'No leads..!', 'lead-form-builder' ) . '</p></div>';
        }
    }
}
