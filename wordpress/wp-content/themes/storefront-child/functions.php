<?php 
require_once('includes/menu_class.php');

/**
 * customize shipping address display - order page and my account
*/
add_filter('woocommerce_order_formatted_shipping_address', 'portolabs_custom_order_formatted_shipping_address', 10, 2); // shipping
add_filter('woocommerce_my_account_my_address_formatted_address', 'portolabs_custom_order_formatted_shipping_address', 10, 2); // my account
function portolabs_custom_order_formatted_shipping_address( $address, $order ) {
    $address['address_1'] = $order->shipping_address_1 . ', ' . $order->shipping_number;
    $address['address_2'] = $order->shipping_address_2 . ' - ' . $order->shipping_neighborhood;
    return $address;
}

remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);


add_action('storefront_child_cart_header', 'storefront_child_header_cart', 60 );
add_action( 'storefront_child_search_header', 'storefront_product_search', 40 );

function wc_ninja_remove_password_strength() {
	if ( wp_script_is( 'wc-password-strength-meter', 'enqueued' ) ) {
		wp_dequeue_script( 'wc-password-strength-meter' );
	}
}
add_action( 'wp_print_scripts', 'wc_ninja_remove_password_strength', 100 );
/* 
 * Change the order of the endpoints that appear in My Account Page - WooCommerce 2.6
 * The first item in the array is the custom endpoint URL - ie http://mydomain.com/my-account/my-custom-endpoint
 * Alongside it are the names of the list item Menu name that corresponds to the URL, change these to suit
 */
function wpb_woo_my_account_order() {
	$myorder = array(
		'orders'             => __( 'Orders', 'woocommerce' ),
		'edit-account'       => __( 'Alterar dados', 'woocommerce' ),
		'edit-address'       => __( 'Endereços', 'woocommerce' ),
		'customer-logout'    => __( 'Sair', 'woocommerce' ),
	);
	return $myorder;
}
add_filter ( 'woocommerce_account_menu_items', 'wpb_woo_my_account_order' );

function wpse_131562_redirect() {
    if (
        ! is_user_logged_in()
        && (is_checkout())
    ) {
        // feel free to customize the following line to suit your needs
        wp_redirect(site_url('minha-conta'));
        exit;
    }
}
add_action('template_redirect', 'wpse_131562_redirect');



remove_action( 'storefront_footer', 'storefront_credit', 50);

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20);


//Adding Registration fields to the form 

add_action( 'register_form', 'adding_custom_registration_fields' );
function adding_custom_registration_fields( ) {

	//lets make the field required so that i can show you how to validate it later;
	$firstname = empty( $_POST['firstname'] ) ? '' : $_POST['firstname'];
	$lastname  = empty( $_POST['lastname'] ) ? '' : $_POST['lastname'];
	$phone = empty( $_POST['phone'] ) ? '' : $_POST['phone'];
	?>
	<div class="line-separator-register"></div>
	<div class="form-row form-row-wide">
		<label for="reg_firstname"><?php _e( 'First Name', 'woocommerce' ) ?><span class="required">*</span></label>
		<input type="text" class="input-text" name="firstname" id="reg_firstname" size="30" value="<?php echo esc_attr( $firstname ) ?>" />
	</div>
	<div class="form-row form-row-wide">
		<label for="reg_lastname"><?php _e( 'Last Name', 'woocommerce' ) ?><span class="required">*</span></label>
		<input type="text" class="input-text" name="lastname" id="reg_lastname" size="30" value="<?php echo esc_attr( $lastname ) ?>" />
	</div>
	<?php
}

//Validation registration form  after submission using the filter registration_errors
add_filter( 'woocommerce_registration_errors', 'registration_errors_validation' );

/**
 * @param WP_Error $reg_errors
 *
 * @return WP_Error
 */
function registration_errors_validation( $reg_errors ) {

	if ( empty( $_POST['firstname'] ) || empty( $_POST['lastname'] ) ) {
		$reg_errors->add( 'Campo obrigatório.', __( 'Por favor preencha todos os campos.', 'woocommerce' ) );
	}

	return $reg_errors;
}

//Updating use meta after registration successful registration
add_action('woocommerce_created_customer','adding_extra_reg_fields');

function adding_extra_reg_fields($user_id) {
	extract($_POST);
	update_user_meta($user_id, 'first_name', $firstname);
	update_user_meta($user_id, 'last_name', $lastname);
	update_user_meta($user_id, 'phone', $phone);
	update_user_meta($user_id, 'billing_first_name', $firstname);
	update_user_meta($user_id, 'shipping_first_name', $firstname);
	update_user_meta($user_id, 'billing_last_name', $lastname);
	update_user_meta($user_id, 'shipping_last_name', $lastname);
	update_user_meta($user_id, 'billing_phone', $phone);
}


// hide coupon field on checkout page
function hide_coupon_field_on_checkout( $enabled ) {
 
	if ( is_checkout() ) {
		$enabled = false;
	}
 
	return $enabled;
}
add_filter( 'woocommerce_coupons_enabled', 'hide_coupon_field_on_checkout' );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
  
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_delivery', 21 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 22 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_clearfix', 23 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_single_variation_add_to_cart_button_submit', 25);


remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );

add_action('woocommerce_checkout_order_review_custom', 'woocommerce_checkout_payment', 20 );




function woocommerce_single_variation_add_to_cart_button_submit() {
	
	global $product;

	if ( ! $product->is_sold_individually() ) {
		woocommerce_quantity_input( array(
			'min_value'   => apply_filters( 'woocommerce_quantity_input_min', 1, $product ),
			'max_value'   => apply_filters( 'woocommerce_quantity_input_max', $product->backorders_allowed() ? '' : $product->get_stock_quantity(), $product ),
			'input_value' => ( isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 )
		) );
	}


	echo '<div class="line-separator-price"></div>
			<input type="hidden" name="add-to-cart" value="'.esc_attr( $product->id ).'" />
	      <button type="submit" class="single_add_to_cart_button button alt">'.esc_html( 'Comprar' ).'</button>';
}


add_filter( 'woocommerce_get_price_html', 'custom_cents_price_html', 100, 2 );
function custom_cents_price_html( $price, $product ){

	$price_del = str_replace('<del>', '<del> De :', $price);
	$price_format = str_replace('<span class="woocommerce-Price-amount amount">', '<span class="woocommerce-Price-amount amount price-number">', $price_del);

    return '' . str_replace( ',', '<span class="cents">,', $price_format );
}





function woocommerce_template_clearfix(){
	echo '<div class="clearfix border-single-product"></div>';
}



function woocommerce_template_delivery() {

	$title_delivery = esc_html(get_field('title-shipping', 'option'));
	$description_delivery = esc_html(get_field('description-shipping', 'option'));

	echo '<div class="info-delivery col-md-6">
			<p class="title-delivery">'.$title_delivery.'</p>
			<p class="description-delivery">'.$description_delivery.'</p>
		</div>';
}


add_filter('loop_shop_columns', 'loop_columns');
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 5; 
	}
}

if ( ! function_exists( 'storefront_header_cart' ) ) {
	
	function storefront_child_header_cart() {
		if ( is_woocommerce_activated() ) {
			if ( is_cart() ) {
				$class = 'current-menu-item';
			} else {
				$class = '';
			}
		?>
		<li class="cart-header <?php echo esc_attr( $class ); ?>">
			<a class="cart-header" href="<?php echo esc_url( WC()->cart->get_cart_url() ); ?>" title="">
				<span class="counts"><?php echo sprintf ( _n( '%d', '%d', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?></span>
				<i class="sprite icon-header-cart"></i> 
			</a>		
				<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
			</li>
		<?php
		}
	}
}

add_action( 'widgets_init',  'widgets_custom_init'  );

function widgets_custom_init() {
	register_sidebar( array(
		'name'          => __( 'Search header', 'storefront' ),
		'id'            => 'search-header',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}


function lallupe_scripts() {
	$template_url = get_template_directory_uri()."-child";
	wp_enqueue_style( 'custom-styles', $template_url."/app/styles/styles.css", array(), '1.0' );
	//wp_enqueue_script( 'bootstrap', $template_url."/app/scripts/src/bootstrap.min.js", array(), '1.0', true );

	wp_enqueue_script( 'app', $template_url."/app/scripts/app.js", array(), '1.0', true );

	wp_enqueue_script( 'owl-carousel', $template_url."/app/scripts/src/owl.carousel.min.js", array(), '1.0', true );

	wp_dequeue_style("storefront-woocommerce-style");
}

add_action( 'wp_enqueue_scripts', 'lallupe_scripts' );

register_nav_menus( array(
				'sidebar-menu'		=> __( 'Sidebar Menu', 'storefront' ),
			) );

if( function_exists('acf_add_options_page') ) {

	acf_add_options_page(array(
		'page_title' 	=> 'Configurações Gerais',
		'menu_title'	=> 'Configurações Gerais',
		'menu_slug' 	=> 'general-config',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));
	
	acf_add_options_page(array(
		'page_title' 	=> 'Slideshow',
		'menu_title'	=> 'Slideshow',
		'menu_slug' 	=> 'slideshow',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Adicionar novo slide',
		'menu_title'	=> 'Adicionar novo slide',
		'parent_slug'	=> 'slideshow',
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Adicionar novo slide mobile',
		'menu_title'	=> 'Adicionar novo slide mobile',
		'parent_slug'	=> 'slideshow',
	));


	acf_add_options_page(array(
		'page_title' 	=> 'Banners',
		'menu_title'	=> 'Banners promocionais',
		'menu_slug' 	=> 'banners-promocionais',
		'capability'	=> 'edit_posts',
		'redirect'		=> false
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> 'Banner principal',
		'menu_title'	=> 'Banner principal',
		'parent_slug'	=> 'banners-promocionais',
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> '1º banner lateral',
		'menu_title'	=> '1º banner lateral',
		'parent_slug'	=> 'banners-promocionais',
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> '2º banner lateral',
		'menu_title'	=> '2º banner lateral',
		'parent_slug'	=> 'banners-promocionais',
	));

	acf_add_options_sub_page(array(
		'page_title' 	=> '3º banner lateral',
		'menu_title'	=> '3º banner lateral',
		'parent_slug'	=> 'banners-promocionais',
	));
}

