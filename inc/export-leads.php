<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class LfbExportLeads extends LFB_SAVE_DB {

    function export_leads( $fomrid, $sdate, $edate, $limit = 20 ) {
        if ( $fomrid === '' ) return;
        global $wpdb;
        $table_name = LFB_FORM_DATA_TBL;
        $new_sdate  = date( 'Y-m-d', strtotime( $sdate ) );
        $new_edate  = date( 'Y-m-d', strtotime( $edate . ' +1 day' ) );
        $query      = $wpdb->prepare(
            "SELECT * FROM $table_name WHERE date >= %s AND date < %s AND form_id = %d ORDER BY id DESC LIMIT $limit",
            $new_sdate, $new_edate, $fomrid
        );
        $result    = $wpdb->get_results( $query );
        $form_data = $this->lfb_get_form_data( $fomrid );
        $field_id  = $this->lfb_form_field_filter( $form_data );
        $this->xl_leads( $field_id, $result );
    }

    function filterData( &$str ) {
        $str = preg_replace( "/\t/", "\\t", $str );
        $str = preg_replace( "/\r?\n/", "\\n", $str );
        if ( strstr( $str, '"' ) ) {
            $str = '"' . str_replace( '"', '""', $str ) . '"';
        }
        return $str;
    }

    function xl_leads( $filedId, $result ) {
        set_time_limit( 0 );
        header( 'Content-Type: text/csv' );
        header( 'Content-Disposition: attachment; filename=export-leads.csv' );
        $field = '';
        foreach ( $filedId as $key => $value ) {
            $field .= $value . "\t";
        }
        $field .= 'Date' . "\n";
        foreach ( $result as $key => $value ) {
            $rvalue = unserialize( $value->form_data );
            foreach ( $filedId as $fkey => $fvalue ) {
                if ( isset( $rvalue[$fkey] ) && is_array( $rvalue[$fkey] ) ) {
                    if ( strstr( $fkey, 'upload_' ) ) {
                        $upload_filename = isset( $rvalue[$fkey]['filename'] ) ? $rvalue[$fkey]['filename'] : $rvalue[$fkey]['error'];
                        $upload  = isset( $rvalue[$fkey]['url'] ) ? $rvalue[$fkey]['url'] : $upload_filename;
                        $field  .= $upload . "\t";
                    } else {
                        $checkbox = $rvalue[$fkey];
                        $xl_impl  = implode( ', ', $checkbox );
                        $field   .= $this->filterData( $xl_impl ) . "\t";
                    }
                } else {
                    $xl_value = isset( $rvalue[$fkey] ) ? $rvalue[$fkey] : '';
                    $field   .= $this->filterData( $xl_value ) . "\t";
                }
            }
            $field .= $value->date . "\n";
        }
        echo chr(255) . chr(254) . iconv( 'UTF-8', 'UTF-16LE//IGNORE', $field );
        exit;
    }
}
