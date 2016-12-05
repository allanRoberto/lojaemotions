<?php
/**
 * Bank Slip - Payment instructions.
 *
 * @author  Iugu
 * @package Iugu_WooCommerce/Templates
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>

<div class="woocommerce-message">
	<span><a class="button button-boleto" href="<?php echo esc_url( $pdf ); ?>" target="_blank"><?php _e( 'Pay the bank slip', 'iugu-woocommerce' ); ?></a></span>
</div>
