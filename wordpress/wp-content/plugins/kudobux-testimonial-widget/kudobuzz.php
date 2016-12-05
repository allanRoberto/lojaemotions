<?php

/*
  Plugin Name: Kudobuzz
  Plugin URI: https://kudobuzz.com
  Description: Collect your business and social reviews from Facebook, Twitter, Instagram, Google+ and Yelp.
  Version: 4.0.4
  Author: Kudobuzz
  Author URI: https://kudobuzz.com
  License: GPL
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );


require_once plugin_dir_path(__FILE__) . 'config.php';
require_once plugin_dir_path(__FILE__) . 'vendor/autoload.php';


add_action('admin_init', 'kudobuzz_plugin_redirect');
add_action('admin_menu', 'register_kudobuzz_menu_page');
Requests::register_autoloader();
use Carbon\Carbon;

/* * ******************************
 * When user activate the plugin
 * ***************************** */
register_activation_hook(__FILE__, 'activate_kudobuzz_plugin');
register_uninstall_hook(__FILE__, 'uninstall_kudobuzz_plugin');




/* * *************************************************************
 * This function is loaded whenever the user clicks on the plugin
 * ************************************************************** */

function kudobuzz_plugin_redirect(){
	$possible_existing_uid = get_option('kudobuzz_uid');
	if (get_option('kudobuzz_activation_redirect', false)) {
		delete_option('kudobuzz_activation_redirect');
		if ( isset( $possible_existing_uid ) && $possible_existing_uid != null && $possible_existing_uid != false ) {
			wp_redirect('admin.php?page=Kudobuzz');
			exit();
		} else {
					wp_redirect('admin.php?page=Signup');
					exit();
		}
	}
}


/* * *************************************************************
 * This function is loaded whenever the user activates the plugin
 * ************************************************************** */
function activate_kudobuzz_plugin(){
	add_option('kudobuzz_fullpage_widget', '<div id="kudobuzz_fullpage_widget"></div>');
	add_option('kudobuzz_slider_widget', '<div id="kudobuzz_slider_widget"></div>');
	add_option('kudobuzz_review_widget', '<div id="kudobuzz_product_reviews_widget"></div>');

	add_option('kudobuzz_login_url', MAIN_HOST . 'login');
	add_option('kudobuzz_activation_redirect', true);
	add_option('kudobuzz_uid', '');
	add_option('slider_widget_added', 0);
	add_option('full_page_widget_added', 0);
	add_action('signin_form', 'sign_up');
	add_action('admin_menu', 'add_submenu_page');
	create_full_page();
}



function do_update_rich_snippet(){
	$kd_uid = get_option('kudobuzz_uid');
	$rs_time = get_option('kudobuzz_rs_time');
	if ( isset( $rs_time ) && $rs_time != null && $rs_time != false ){
		$now = Carbon::now();

		if($rs_time >= $now){
			if (isset($kd_uid) && $kd_uid !== FALSE && !empty($kd_uid)) {
				$headers = array('Content-Type' => 'application/json');
				$url = API_URL.'widget/accounts/'. $kd_uid;
				$options = array('timeout' => 60,);
				$response = Requests::get($url, $headers, null, $options);

				if($response->status_code == 200){
					$json_data = json_decode($response->body);
					if(in_array($json_data->account->payment_plan->plan->id, array(3,4,7,8))){
						$headers = array('Content-Type' => 'application/json');
						$url = API_URL.'widget/reviews/'. $kd_uid;
						$options = array('timeout' => 60,);
						$response = Requests::get($url, $headers, null, $options);

						if($response->status_code == 200){
							$json_data = json_decode($response->body);
							$total = 0;
							foreach ($json_data->site_reviews->rating_breakdown as $key => $value){
								$total = $total + $value;
							}
							update_option('kudobuzz_ratingValue', $json_data->site_reviews->avg_rating);
							update_option('kudobuzz_ratingCount', $total);
							$tommorrow = Carbon::tomorrow();
							update_option('kudobuzz_rs_time', $tommorrow);
						}
					}else{
						update_option('kudobuzz_ratingValue', '');
						update_option('kudobuzz_ratingCount', '');
					}

				}else{
					update_option('kudobuzz_ratingValue', '');
					update_option('kudobuzz_ratingCount', '');
				}

			}else{
				update_option('kudobuzz_ratingValue', '');
				update_option('kudobuzz_ratingCount', '');
			}
		}
	}else{
		$today = Carbon::today();
		add_option('kudobuzz_rs_time', $today);
	}


}

function create_full_page(){
	$the_page_title = 'Testimonials';
	$the_page_name = 'kudobuzz-full-page-widget';

	// the menu entry...
	delete_option("kudobuzz_page_title");
	add_option("kudobuzz_page_title", $the_page_title, '', 'yes');
	// the slug...
	delete_option("kudobuzz_page_title");
	add_option("kudobuzz_page_name", $the_page_name, '', 'yes');
	// the id...
	delete_option("kudobuzz_plugin_page_id");
	add_option("kudobuzz_plugin_page_id", '0', '', 'yes');

	$the_page = get_page_by_title($the_page_title);

	if (!$the_page) {

		// Create post object
		$_p = array();
		$_p['post_title'] = $the_page_title;
		$_p['post_content'] = "[kudobuzz-fullpage]";
		$_p['post_status'] = 'publish';
		$_p['post_type'] = 'page';
		$_p['comment_status'] = 'closed';
		$_p['ping_status'] = 'closed';
		$_p['post_category'] = array(1); // the default 'Uncatrgorised'
		// Insert the post into the database
		$the_page_id = wp_insert_post($_p);
	} else {

		// the plugin may have been previously active and the page may just be trashed...

		$the_page_id = $the_page->ID;

		//make sure the page is not trashed...
		$the_page->post_status = 'publish';
		$the_page_id = wp_update_post($the_page);
	}

	delete_option('kudobuzz_plugin_page_id');
	add_option('kudobuzz_plugin_page_id', $the_page_id);

}

function register_kudobuzz_menu_page(){
	add_menu_page(__('kudobuzz_menu', 'Kudobuzz'), __('Kudobuzz', 'kudos-menu'), 'manage_options', 'Kudobuzz', 'moderate_reviews', plugins_url('kudobux-testimonial-widget/assets/img/kudobuzz_logo.png'));

	//Sign up
	add_submenu_page('kudobuzz_menu', 'Signup', 'Signup', 'manage_options', 'Signup', 'signup_now');

	//After registration
	add_submenu_page('kudobuzz_menu', 'Success Page', 'Success Page', 'manage_options', 'Success', 'go_success_page');

	//Moderate review page
	add_submenu_page('kudobuzz_menu', 'ModerateReviews', 'ModerateReviews', 'manage_options', 'ModerateReviews', 'moderate_reviews');

	//Inject code in the head tag
	add_submenu_page('kudobuzz_menu', 'Inject code in the head tag', 'Inject code in the head tag', 'manage_options', 'success', 'inject_code');

	add_submenu_page('Kudobuzz', 'Installation Steps', 'Documentation', 'manage_options', 'InstallationInstruction', 'installation_instruction');

	add_submenu_page('Kudobuzz', 'Upgrade', 'Upgrade', 'manage_options', 'upgrade', 'upgrade');

	add_submenu_page('Kudobuzz', 'Leave a Review', 'Leave a Review', 'manage_options', 'leave_a_review', 'leave_a_review');

	add_submenu_page('Kudobuzz', 'Contact Support', 'Contact Support', 'manage_options', 'contact_support', 'contact_support');
}

/*
 * Signup form
 */

function signup_now() {
	$possible_existing_uid = get_option('kudobuzz_uid');

	if (isset($possible_existing_uid) && $possible_existing_uid !== FALSE && !empty($possible_existing_uid)) {
		moderate_reviews();
	} else {
		include( plugin_dir_path(__FILE__) . '/includes/new-user-form.php');
	}

}

function wpd_add_kudobuzz_css_files() {
	if (is_admin()) {
		//Main css file
		wp_register_style('main-css', plugins_url('kudobux-testimonial-widget/assets/css/main.css', dirname(__FILE__)), false, '1.0.0');
		wp_enqueue_style('main-css');

		//bootstrap css file
		wp_register_style('bootstrap-css', plugins_url('kudobux-testimonial-widget/assets/css/bootstrap.css', dirname(__FILE__)), false, '3.0.1');
		wp_enqueue_style('bootstrap-css');

	}
}


function wpd_add_kb_woo_css_files(){
	if (is_admin()) {
		wp_register_style( 'kb_woo_fix', plugins_url( 'kudobux-testimonial-widget/assets/css/kb_woo_fix.css', dirname( __FILE__ ) ), false, '1.0.0' );
		wp_enqueue_style( 'kb_woo_fix' );
	}
}

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	add_action('admin_enqueue_scripts', 'wpd_add_kb_woo_css_files');
}else{
	add_action('admin_enqueue_scripts', 'wpd_add_kudobuzz_css_files');
}



function wpd_add_kudobuzz_javascript_files() {
	wp_enqueue_script("jquery");
}
add_action('admin_enqueue_scripts', 'wpd_add_kudobuzz_javascript_files');


//Add action for creating an account
add_action('wp_ajax_post_create_account_action', 'post_create_account_action');

//Add action for creating a woocommerce account
add_action("wp_ajax_post_create_account_woo_action", "post_create_account_woo_action");


function post_create_account_action() {
	$email = $_POST["email"];
	$account_name = $_POST["account_name"];
	$url = $_POST["url"];
	$password = $_POST["password"];
	$platform = $_POST["platform"];

	$data = array(
		"email" => $email,
		"account_name" => $account_name,
		"platform" => $platform,
		"password" => $password,
		"url" => $url
	);

	$headers = array('Content-Type' => 'application/json');
	$url = API_URL.'users/create';
	$options = array('timeout' => 60,);
	$response = Requests::post($url, $headers, json_encode($data), $options);
	try {
		if ( $response->status_code == 201 ) {
			$json_data = json_decode( $response->body );
			update_option( 'kudobuzz_uid', $json_data->uid );
			update_option( 'kudobuzz_token', $json_data->token );
			update_option( 'kudobuzz_user_id', $json_data->user->id );
			update_option( "kudobuzz_email", $json_data->user->email );
			echo 1;
			wp_die();
		} else {
			echo 0;
			wp_die();
		}
	}catch (Exception $e){
		echo 0;
		wp_die();
	}

}

function post_create_account_woo_action(){
	$email = $_POST["email"];
	$account_name = $_POST["account_name"];
	$consumer_key = $_POST["consumer_key"];
	$consumer_secret = $_POST["consumer_secret"];
	$platform = $_POST["platform"];
	$password = $_POST["password"];
	$url = $_POST["url"];
	$url_arr = parse_url($url);
	$plaftorm_domain = $url_arr["host"];

	$data = array(
		"email" => $email,
		"account_name" => $account_name,
		"consumer_key" => $consumer_key,
		"consumer_secret" => $consumer_secret,
		"platform" => $platform,
		"password" => $password,
		"platform_domain" => $plaftorm_domain,
		"url" => $url
	);

	$headers = array('Content-Type' => 'application/json');
	$url = API_URL.'users/create';
	$options = array('timeout' => 60,);
	$response = Requests::post($url, $headers, json_encode($data), $options);
	try{
		if($response->status_code == 201){
			$json_data = json_decode($response->body);
			update_option('kudobuzz_uid', $json_data->uid);
			update_option('kudobuzz_token', $json_data->token);
			update_option('kudobuzz_user_id', $json_data->user->id);
			update_option("kudobuzz_email", $json_data->user->email);
			echo 1;
			wp_die();
		}else{
			echo 0;
			wp_die();
		}
	}catch (Exception $e){
		echo 0;
		wp_die();
	}
}


/*
 * Success page
 */

function go_success_page() {
	include( plugin_dir_path(__FILE__) . '/includes/success.php');
}


/*
 * Signin form
 */
/*
 * Moderate reviews
 */

function moderate_reviews() {

	do_update_rich_snippet();

	$kd_uid = get_option('kudobuzz_uid');
	$kudobuzz_account_id = get_option('kudobuzz_account_id');
	$kudobuzz_user_id = get_option('kudobuzz_user_id');
	$kudobuzz_token = get_option('kudobuzz_token');
	$kudobuzz_email = get_option('kudobuzz_email');
	$possible_existing_uid= get_option("kudobuzz_uid");

	include( plugin_dir_path(__FILE__) . '/includes/moderate_reviews.php');
}

/*
 * Inject code
 */

function inject_code() {
	include( plugin_dir_path(__FILE__) . '/includes/success.php');
}

$uid2 = get_option('kudobuzz_uid');

if (isset($uid2) && $uid2 !== FALSE && !empty($uid2)) {

	$script = "<!--Start Kudobuzz Here --> <script type='text/javascript'>!function(){var e=document.createElement('script');e.type='text/javascript',e.async=!0;var t=location.protocol+'" . WIDGET_URL . "js/widgetLoader.js';e.src=t;document.getElementsByTagName('head')[0].appendChild(e);window.Kudos={Widget:function(e){this.uid=e.uid}},Kudos.Widget({uid:'" . get_option('kudobuzz_uid') . "'})}();</script><!--End Kudobuzz Here -->";

//Get embedable widgets
	$slider_widget_added = get_option('slider_widget_added');
	$full_page_widget_added = get_option('full_page_widget_added');

	function add_widget() {

		echo $GLOBALS['script'];
	}

	add_action('wp_head', 'add_widget');
}


/**
 * Set code full page
 */
add_shortcode("kudobuzz-fullpage", 'set_fullpage_shortcode');

function set_fullpage_shortcode($atts) {
	$kudobuzz_fullpage_tag = "";
	$kudobuzz_fullpage_tag .= get_option("kudobuzz_fullpage_widget");
	return $kudobuzz_fullpage_tag;
}

/**
 * Set code slider
 */
add_shortcode("kudobuzz-slider", "set_slider_shortcode");

function set_slider_shortcode($atts) {
	$kudobuzz_slider_tag = "";
	$kudobuzz_slider_tag .= get_option("kudobuzz_slider_widget");
	return $kudobuzz_slider_tag;
}

/**
 * Set code reviewer
 */
add_shortcode("kudobuzz-page-review", "set_page_review_shortcode");

function set_page_review_shortcode($atts) {
	$kudobuzz_slider_tag = "";
	$kudobuzz_slider_tag .= get_option("kudobuzz_review_widget");
	return $kudobuzz_slider_tag;
}

add_shortcode("site_rich_snippet", "set_site_rich_snippet");

function set_site_rich_snippet($atts){
	return '
			<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
          		<meta itemprop="itemReviewed" content="'.get_site_url().'>
                <span itemprop="ratingValue">'.get_option('kudobuzz_ratingValue').'</span>
                <span itemprop="ratingCount">'.get_option('kudobuzz_ratingCount').'</span>
            </div>
	';
}
/*
 * Installation steps
 */

function installation_instruction() {

	include( plugin_dir_path(__FILE__) . '/includes/installation-instruction.php');
}

function upgrade(){

	$kd_uid = get_option('kudobuzz_uid');
	$kudobuzz_account_id = get_option('kudobuzz_account_id');
	$kudobuzz_user_id = get_option('kudobuzz_user_id');
	$kudobuzz_token = get_option('kudobuzz_token');
	$kudobuzz_email = get_option('kudobuzz_email');
	$possible_existing_uid= get_option("kudobuzz_uid");

	include( plugin_dir_path(__FILE__) . '/includes/upgrade.php');
}

function leave_a_review(){
	$kd_uid = get_option('kudobuzz_uid');
	$kudobuzz_account_id = get_option('kudobuzz_account_id');
	$kudobuzz_user_id = get_option('kudobuzz_user_id');
	$kudobuzz_token = get_option('kudobuzz_token');
	$kudobuzz_email = get_option('kudobuzz_email');
	$possible_existing_uid= get_option("kudobuzz_uid");

	include( plugin_dir_path(__FILE__) . '/includes/leave-a-review.php');
}


add_action( 'wp_footer', 'inject_rich_snippet');


function inject_rich_snippet(){
	echo do_shortcode('[site_rich_snippet]');
}


function contact_support(){
	include( plugin_dir_path(__FILE__) . '/includes/support.php');
}
