<?php
/*
 * 
Plugin Name: Woocommerce Order Export Import(BASIC)
Plugin URI: http://www.xadapter.com/product/order-import-export-plugin-for-woocommerce/
Description: Export and Import Order detail including line items, From and To your WooCommerce Store.
Author: HikeForce
Author URI: http://www.xadapter.com/vendor/hikeforce/
Version: 1.0.5
Text Domain: wf_order_import_export
*/

if ( ! defined( 'ABSPATH' ) || ! is_admin() ) {
	return;
}

define( "WF_ORDER_IMP_EXP_ID", "wf_order_imp_exp" );
define( "WF_WOOCOMMERCE_ORDER_IM_EX", "wf_woocommerce_order_im_ex" );

/**
 * Check if WooCommerce is active
 */
if (in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) )) {	

	if ( ! class_exists( 'WF_Order_Import_Export_CSV' ) ) :

	/**
	 * Main CSV Import class
	 */
	class WF_Order_Import_Export_CSV {

		/**
		 * Constructor
		 */
		public function __construct() {
			define( 'WF_OrderImpExpCsv_FILE', __FILE__ );

                        if ( is_admin() ) { 
                                add_action( 'admin_notices', array( $this, 'wf_rate_admin_notice'), 16);
			}

			add_filter( 'woocommerce_screen_ids', array( $this, 'woocommerce_screen_ids' ) );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'wf_plugin_action_links' ) );
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
			add_action( 'init', array( $this, 'catch_export_request' ), 20 );
			add_action( 'init', array( $this, 'catch_save_settings' ), 20 );
			add_action( 'admin_init', array( $this, 'register_importers' ) );

			include_once( 'includes/class-wf-orderimpexpcsv-system-status-tools.php' );
			include_once( 'includes/class-wf-orderimpexpcsv-admin-screen.php' );
			include_once( 'includes/importer/class-wf-orderimpexpcsv-importer.php' );

			if ( defined('DOING_AJAX') ) {
				include_once( 'includes/class-wf-orderimpexpcsv-ajax-handler.php' );
			}
		}
		
		public function wf_plugin_action_links( $links ) {
			$plugin_links = array(
				'<a href="' . admin_url( 'admin.php?page=wf_woocommerce_order_im_ex' ) . '">' . __( 'Import Export', 'wf_order_import_export' ) . '</a>',
                                '<a href="http://www.xadapter.com/product/order-import-export-plugin-for-woocommerce/" target="_blank" style="color:#3db634;">' . __( 'Premium Upgrade', 'wf_order_import_export' ) . '</a>',	
                                '<a href="http://www.xadapter.com/support/forum/order-import-export-plugin-for-woocommerce/">' . __( 'Support', 'wf_order_import_export' ) . '</a>',
			);
			return array_merge( $plugin_links, $links );
		}
		
                function wf_rate_admin_notice() {
                    global $pagenow;
                    if(isset($_GET['page']) && $_GET['page'] === 'wf_woocommerce_order_im_ex' && 'admin.php' === $pagenow){
                    if (!get_option('dismiss_rateus')){
                ?>
                    <div id="dismiss_rateus" class="updated settings-error notice is-dismissible">
                        <p><a target="_blank" href="https://wordpress.org/support/view/plugin-reviews/order-import-export-for-woocommerce#postform">
                           <?php _e('Did this plugin work for you?&nbsp; Please rate and contact us at <i>info@xadapter.com</i> to get $10 off on your next purchase.', 'wf_order_import_export'); ?></a>&nbsp;&nbsp;
                           <a target="_blank" class="button-primary" href="https://wordpress.org/support/view/plugin-reviews/order-import-export-for-woocommerce#postform" ><?php _e('Review Now!' , 'wf_order_import_export'); ?></a>
                           <a target="_blank" class="rate-star" href="https://wordpress.org/support/view/plugin-reviews/order-import-export-for-woocommerce#postform"></a>&nbsp;&nbsp;
                        </p>
                    </div>
                <?php
                        }
                    }
                }
		/**
		 * Add screen ID
		 */
		public function woocommerce_screen_ids( $ids ) {
			$ids[] = 'admin'; // For import screen
			return $ids;
		}

		/**
		 * Handle localisation
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain( 'wf_order_import_export', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		/**
		 * Catches an export request and exports the data. This class is only loaded in admin.
		 */
		public function catch_export_request() {
			if ( ! empty( $_GET['action'] ) && ! empty( $_GET['page'] ) && $_GET['page'] == 'wf_woocommerce_order_im_ex' ) {
				switch ( $_GET['action'] ) {
					case "export" :
						include_once( 'includes/exporter/class-wf-orderimpexpcsv-exporter.php' );
						WF_OrderImpExpCsv_Exporter::do_export( 'shop_order' );
					break;
				}
			}
		}
		
		public function catch_save_settings() {
			if ( ! empty( $_GET['action'] ) && ! empty( $_GET['page'] ) && $_GET['page'] == 'wf_woocommerce_order_im_ex' ) {
				switch ( $_GET['action'] ) {
					case "settings" :
						include_once( 'includes/settings/class-wf-orderimpexpcsv-settings.php' );
						WF_OrderImpExpCsv_Settings::save_settings( );
					break;
				}
			}
		}

		/**
		 * Register importers for use
		 */
		public function register_importers() {
			register_importer( 'woocommerce_wf_order_csv', 'WooCommerce Order (CSV)', __('Import <strong>Orders</strong> to your store via a csv file.', 'wf_order_import_export'), 'WF_OrderImpExpCsv_Importer::order_importer' );
		}
	}
	endif;

	new WF_Order_Import_Export_CSV();

}
