<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WF_OrderImpExpCsv_AJAX_Handler {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'wp_ajax_woocommerce_csv_order_import_request', array( $this, 'csv_order_import_request' ) );
	}
	
	/**
	 * Ajax event for importing a CSV
	 */
	public function csv_order_import_request() {
		define( 'WP_LOAD_IMPORTERS', true );
                WF_OrderImpExpCsv_Importer::order_importer();
	}

	/**
	 * Die with a JSON formatted error message
	 */
	public function die_json_error_msg( $id, $message ) {
        die( json_encode( array( 'error' => sprintf( __( '&quot;%1$s&quot; (ID %2$s) failed to resize. The error message was: %3$s', 'regenerate-thumbnails' ), esc_html( get_the_title( $id ) ), $id, $message ) ) ) );
    }	
}

new WF_OrderImpExpCsv_AJAX_Handler();