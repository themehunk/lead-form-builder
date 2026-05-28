<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class LFBFormImportExport {

    function exportXml( $fid ) {
        $obj          = new LFB_SAVE_DB();
        $combine_data = $obj->get_combined_form_data( $fid );
        $json_data    = json_encode( $combine_data, JSON_PRETTY_PRINT );

        header( 'Content-Type: application/json' );
        header( 'Content-Disposition: attachment; filename="form_' . intval( $fid ) . '.json"' );
        header( 'Content-Length: ' . strlen( $json_data ) );
        echo $json_data;
        exit();
    }

    function importXml() {
        if ( empty( $_FILES['lfb_file_import']['name'] ) ) return;
        $filename = sanitize_file_name( $_FILES['lfb_file_import']['name'] );
        $ext      = strtolower( pathinfo( $filename, PATHINFO_EXTENSION ) );
        if ( $ext !== 'json' ) return;

        $json_content = file_get_contents( $_FILES['lfb_file_import']['tmp_name'] );
        $data         = json_decode( $json_content, true );
        if ( ! is_array( $data ) || empty( $data[0] ) ) return;

        $obj = new LFB_SAVE_DB();
        $obj->import_complete_data( $data[0] );
    }
}
