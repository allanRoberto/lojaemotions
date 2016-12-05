<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woothemes.com/document/template-structure/
 * @author  WooThemes
 * @package WooCommerce/Templates
 * @version 2.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
	$template_url = get_template_directory_uri().'-child/app/';
	$status = wc_get_order_statuses();
	$status_name = wc_get_order_status_name( $order->get_status() );

	switch ($status_name) {
		case 'Pagamento pendente':?>
			<ul class="navigation-status">
				<li class="order-status-payment-pending active">
					<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-payment-pending.png"/>
					<span class="title-order-status">Pagamento pendente</span>
				</li>
				<li class="order-status-payment-aproved">
					<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-payment-aproved.png"/>
					<span class="title-order-status">Pagamento aprovado</span>
				</li>
				<li class="order-status-processing">
					<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-processing.png"/>
					<span class="title-order-status">Processando pedido</span>
				</li>
				<li class="order-status-shipping">
					<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-shipping.png"/>
					<span class="title-order-status">Pedido em transporte</span>
				</li>
				<li class="order-status-complete">
					<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-complete.png"/>
					<span class="title-order-status">Pedido concluído</span>
				</li>
			</ul>
			<?php break;

		case 'Pagamento aprovado':?>
			<ul class="navigation-status">
				<li class="order-status-payment-pending active">
					<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-payment-pending.png"/>
					<span class="title-order-status">Pagamento pendente</span>
				</li>
				<li class="order-status-payment-aproved">
					<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-payment-aproved.png"/>
					<span class="title-order-status">Pagamento aprovado</span>
				</li>
				<li class="order-status-processing">
					<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-processing.png"/>
					<span class="title-order-status">Processando pedido</span>
				</li>
				<li class="order-status-shipping">
					<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-shipping.png"/>
					<span class="title-order-status">Pedido em transporte</span>
				</li>
				<li class="order-status-complete">
					<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-complete.png"/>
					<span class="title-order-status">Pedido concluído</span>
				</li>
			</ul>
			<?php break;	

		case 'Processando pedido':?>
			<ul class="navigation-status">
				<li class="order-status-payment-pending active">
					<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-payment-pending.png"/>
					<span class="title-order-status">Pagamento pendente</span>
				</li>
				<li class="order-status-payment-aproved">
					<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-payment-aproved.png"/>
					<span class="title-order-status">Pagamento aprovado</span>
				</li>
				<li class="order-status-processing">
					<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-processing.png"/>
					<span class="title-order-status">Processando pedido</span>
				</li>
				<li class="order-status-shipping">
					<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-shipping.png"/>
					<span class="title-order-status">Pedido em transporte</span>
				</li>
				<li class="order-status-complete">
					<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-complete.png"/>
					<span class="title-order-status">Pedido concluído</span>
				</li>
			</ul>
			<?php break;
		
			case 'Pedido em transporte' : ?>
				<ul class="navigation-status">
					<li class="order-status-payment-pending active">
						<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-payment-pending.png"/>
						<span class="title-order-status">Pagamento pendente</span>
					</li>
					<li class="order-status-payment-aproved">
						<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-payment-aproved.png"/>
						<span class="title-order-status">Pagamento aprovado</span>
					</li>
					<li class="order-status-processing">
						<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-processing.png"/>
						<span class="title-order-status">Processando pedido</span>
					</li>
					<li class="order-status-shipping">
						<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-shipping.png"/>
						<span class="title-order-status">Pedido em transporte</span>
					</li>
					<li class="order-status-complete">
						<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-complete.png"/>
						<span class="title-order-status">Pedido concluído</span>
					</li>
				</ul>
			<?php break;
		
			case 'Pedido entregue': ?>
				<ul class="navigation-status">
					<li class="order-status-payment-pending active">
						<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-payment-pending.png"/>
						<span class="title-order-status">Pagamento pendente</span>
					</li>
					<li class="order-status-payment-aproved">
						<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-payment-aproved.png"/>
						<span class="title-order-status">Pagamento aprovado</span>
					</li>
					<li class="order-status-processing">
						<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-processing.png"/>
						<span class="title-order-status">Processando pedido</span>
					</li>
					<li class="order-status-shipping">
						<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-shipping.png"/>
						<span class="title-order-status">Pedido em transporte</span>
					</li>
					<li class="order-status-complete">
						<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-complete.png"/>
						<span class="title-order-status">Pedido concluído</span>
					</li>
				</ul>
			<?php break;

			case 'Pedido em análise' : ?>
				<ul class="navigation-status">
					<li class="order-status- active">
						<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-.png"/>
						<span class="title-order-status">Pedido cancelado</span>
					</li>
				</ul>
			<?php break;
		
			case 'Pedido Cancelado' :?>
				<ul class="navigation-status">
					<li class="order-status-canceled active">
						<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-canceled.png"/>
						<span class="title-order-status">Pedido cancelado</span>
					</li>
				</ul>
			<?php break;
			
			case 'Pedido reembolsado' :?>
				<ul class="navigation-status">
					<li class="order-status-payment-pending active">
						<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-payment-pending.png"/>
						<span class="title-order-status">Pagamento pendente</span>
					</li>
					<li class="order-status-payment-aproved">
						<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-payment-aproved.png"/>
						<span class="title-order-status">Pagamento aprovado</span>
					</li>
					<li class="order-status-processing">
						<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-processing.png"/>
						<span class="title-order-status">Processando pedido</span>
					</li>
					<li class="order-status-shipping">
						<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-shipping.png"/>
						<span class="title-order-status">Pedido em transporte</span>
					</li>
					<li class="order-status-complete">
						<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-complete.png"/>
						<span class="title-order-status">Pedido concluído</span>
					</li>
					<li class="order-status-return">
						<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-return.png"/>
						<span class="title-order-status">Pedido reembolsado</span>
					</li>
				</ul>
			<?php break;
			
			case 'Pedido Falhado' :?>
				<ul>
				 <li class="order-status-failed active">
				 	<img class="img-responsive" src="<?php echo $template_url ?>/images/order-status-failed.png"/>
				 	<span class="title-order-status">Pedido falhado</span>
				 </li>
				</ul>
			<?php break;
	}

?>
<p class="info-order">
	<span>Número do pedido : <mark><?php echo $order->get_order_number(); ?></mark></span>
	<span>Data do pedido : <mark><?php echo date_i18n( get_option( 'date_format' ), strtotime( $order->order_date ) ); ?></mark></span>
</p>

<?php do_action( 'woocommerce_view_order', $order_id ); ?>

<?php if ( $notes = $order->get_customer_order_notes() ) : ?>
	<h2><?php _e( 'Order Updates', 'woocommerce' ); ?></h2>
	<ol class="woocommerce-OrderUpdates commentlist notes">
		<?php foreach ( $notes as $note ) : ?>
		<li class="woocommerce-OrderUpdate comment note">
			<div class="woocommerce-OrderUpdate-inner comment_container">
				<div class="woocommerce-OrderUpdate-text comment-text">
					<p class="woocommerce-OrderUpdate-meta meta"><?php echo date_i18n( __( 'l jS \o\f F Y, h:ia', 'woocommerce' ), strtotime( $note->comment_date ) ); ?></p>
					<div class="woocommerce-OrderUpdate-description description">
						<?php echo wpautop( wptexturize( $note->comment_content ) ); ?>
					</div>
	  				<div class="clear"></div>
	  			</div>
				<div class="clear"></div>
			</div>
		</li>
		<?php endforeach; ?>
	</ol>
<?php endif; ?>

