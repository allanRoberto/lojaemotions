<?php
/**
 * Created by PhpStorm.
 * User: Larry
 * Date: 7/19/16
 * Time: 12:05 PM
 */

?>
<div class="main-wrapper">
	<div class="main-app-wrapper">
		<div id="title-div">
			KUDOBUZZ
		</div>
		<div class="main-app-content">

			<div style="margin: 0px auto 10px auto; width: 100%; overflow: hidden; padding: 0 20px;">
				<div class="pull-left" style="width: 420px;">

					<h2 style="margin-top: 50px; font-size: 30px" class="main-title">Create an Account</h2>
					<div class="pull-left" style="width: 400px; margin-right: 20px">
						<h3 style="color: #d35400">
							Collecting Social Testimonials<br> made simple
						</h3>
						<p style="margin-top: 30px">
							People are talking about your brand, start showing off positive social buzz easily on your website.
						</p>
						<ul>
							<li>Easy to setup</li>
							<li>Real-time testimonial updates</li>
							<li>Clean &amp; easy to customize widget</li>
						</ul>
					</div>
				</div>
				<div class="pull-left" style="width: 410px; padding: 40px 20px;">

					<form role="form" id="new-user-form" class="<?php echo isset($user_id) && !empty($user_id) ? 'hide' : '' ?>">
						<?php
						if ( false ) {
							echo '
								<div class="container">
									<div class="form-group">
										<label for="email">Choose Platform</label>
										<div>
											<label style="margin-right: 20px;"><input type="radio" value="wordpress" name="platform" checked="true" /> Wordpress</label>
											<label><input type="radio" name="platform" value="woocommerce" /> Woocommerce</label>
										</div>
									</div>
								</div>
							
							';
						} else {
							echo '
								<div class="container hidden">
									<div class="form-group">
										<label for="email">Choose Platform</label>
										<div>
											<label style="margin-right: 20px;"><input type="radio" value="wordpress" name="platform" checked="true" /> Wordpress</label>
											<label><input type="radio" name="platform" value="woocommerce" /> Woocommerce</label>
										</div>
									</div>
								</div>
							';
						}
						?>

						<!-- Email -->
						<div class="container">
							<div class="form-group">
								<label for="email">Email address</label>
								<input type="text" class="form-control input-sm" id="email" value="<?php echo get_option('admin_email'); ?>" maxlength="50">
								<br><span class="feedback"></span>
							</div>
						</div>


						<!-- Account name -->
						<div class="container">
							<div class="form-group" style="width: 555px;">
								<label for="account-name">Site Name</label>
								<div class="form-control input-sm" style="color: #000; font-weight: bold; width: 332px;   padding: 0 8px !important;">
									https://kudobuzz.com/<input type="text" id="account-name" placeholder="site_name" class="no-border" maxlength="30" style="width:195px;">
								</div>
								<br><span class="feedback" style="width: 225px; font-weight: normal"></span>
							</div>
						</div>

						<div class="container">
							<div class="form-group" style="width: 352px;">
								<label for="url">Website URL</label>
								<div style="color: #000; font-weight: bold;">
									<input type="text" id="url" placeholder="http://mywebsite.com" class="form-control input-sm" value="<?php echo get_site_url(); ?>" />
									<span class="feedback" style="width: 180px; font-weight: normal"></span>
								</div>
							</div>
						</div>

						<!-- Password -->
						<div class="container pass_div">
							<div class="form-group">
								<label for="password">Password</label>
								<input type="password" class="form-control input-sm" id="password" placeholder="Password" maxlength="10">
								<br><span class="feedback"></span>
							</div>
						</div>

						<?php
						if ( false ) {
							echo '<div class="container woo">
									<div class="form-group" style="width: 352px;">
										<label for="url">For Woocommerce users only</label>
										<div style="color: #000; font-weight: bold; width: 100%; overflow: hidden">
											<div style="width: 165px; float: left; overflow: hidden;">
												<input type="text" id="consumer_key" placeholder="Consumer KEY" class="form-control input-sm" style="width: 100%;"/>
												<span class="feedback" style="width: 100%; font-weight: normal"></span>
											</div>
											<div style="width: 165px; float: right; overflow: hidden;">
												<input type="text" id="consumer_secret" placeholder="Consumer SECRET" class="form-control input-sm" style="width: 100%;"/>
												<span class="feedback" style="width: 100%; font-weight: normal"></span>
											</div>
										</div>
									</div>
								 </div>';
						} else {

						}
						?>

						<div>
							<span id="fb" class="hide" style="margin-left: 10px;color:red;"></span>
						</div>
						<button type="button" class="btn btn-info btn-sm" id="create-user-btn">Create Account</button>
						<a class="are_you_new" href="http://app.kudobuzz.com/login" target="_blank" style="margin-left: 20px; font-weight: bold; font-size: 14px;">Are you an existing user?</a>
						<div id="error"></div>
					</form>

				</div>
			</div>
		</div>
		<div id="copyright-div">
			&copy; 2016 Kudobuzz
		</div>
	</div>
</div>

<script>


	jQuery(document).on("click", "#create-user-btn", function () {

		var selected_platform = jQuery("input[name='platform']:checked").val();
		var email = jQuery("#email").val();
		var url = jQuery("#url").val();
		var account_name = jQuery("#account-name").val();
		var password = jQuery("#password").val();
		var consumer_key = jQuery("#consumer_key").val();
		var consumer_secret = jQuery("#consumer_secret").val();
		var create = true;

		if (email.length == 0){
			jQuery("#fb").css({'color': 'red'});
			jQuery("#fb").addClass('hide');
			jQuery("#create-user-btn").removeClass('hide');
			jQuery("input").prop("disabled", false);
			jQuery('#error').append("Please refresh your page  and enter your email");
			create = false;
		}

		if (url.length == 0){
			jQuery("#fb").css({'color': 'red'});
			jQuery("#fb").addClass('hide');
			jQuery("#create-user-btn").removeClass('hide');
			jQuery("input").prop("disabled", false);
			jQuery('#error').append("Please refresh your page and enter your url");
			create = false;
		}

		if(password.length == 0){
			jQuery("#fb").css({'color': 'red'});
			jQuery("#fb").addClass('hide');
			jQuery("#create-user-btn").removeClass('hide');
			jQuery("input").prop("disabled", false);
			jQuery('#error').append("Please refresh your page and enter a password");
			create = false;
		}

		if(account_name.length == 0){
			jQuery("#fb").css({'color': 'red'});
			jQuery("#fb").addClass('hide');
			jQuery("#create-user-btn").removeClass('hide');
			jQuery("input").prop("disabled", false);
			jQuery('#error').append("Please refresh your page and enter site_name");
			create = false;
		}

		if(selected_platform == 'woocommerce'){
			if(consumer_key.length == 0){
				jQuery("#fb").css({'color': 'red'});
				jQuery("#fb").addClass('hide');
				jQuery("#create-user-btn").removeClass('hide');
				jQuery("input").prop("disabled", false);
				jQuery('#error').append("Please refresh your page and enter your consumer_key");
				create = false;
			}
			if(consumer_secret.length == 0){
				jQuery("#fb").css({'color': 'red'});
				jQuery("#fb").addClass('hide');
				jQuery("#create-user-btn").removeClass('hide');
				jQuery("input").prop("disabled", false);
				jQuery('#error').append("Please refresh your page and enter your consumer_secret")
				create = false;
			}
		}





		jQuery("#fb").css({'color': 'green'});
		jQuery("#fb").removeClass('hide');
		jQuery("#fb").html('<img src="<?php echo plugins_url() ?>/kudobux-testimonial-widget/assets/img/loader.gif" /> <span>Please wait...</span>');
		jQuery("#create-user-btn").addClass('hide');
		jQuery("input").prop("disabled", true);

		jQuery(".are_you_new").addClass("hide");

		if(create){
			if (selected_platform == "woocommerce") {
				create_woo_shop(email, url, account_name, consumer_key, consumer_secret, password);
			} else if (selected_platform == 'wordpress') {
				create_word_account(email, url, account_name, password);
			}
		}


	});


	function create_woo_shop(email, url, account_name, consumer_key, consumer_secret, password) {

		var mydata = {
			'email': email,
			'account_name': account_name,
			'consumer_key': consumer_key,
			'consumer_secret': consumer_secret,
			'url': url,
			'password': password,
			'platform': 'woocommerce',
			'action': 'post_create_account_woo_action'
		};
		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: mydata,
			dataType : "json",
			error: function (data) {
				console.log(data);
			},
			success: function (data, textStatus, jqXHR) {

				if (data == 1) {
					jQuery("#form-li a").removeAttr('data-toggle');
					window.location.href = "admin.php?page=Success";
					jQuery(".are_you_new").addClass("hide");
				} else if(data == 0)  {
					jQuery("#fb").css({'color': 'green'});
					jQuery("#fb").addClass('hide');
					jQuery("#create-user-btn").removeClass('hide');
					jQuery("input").prop("disabled", false);
					jQuery('#error').append("Something went wrong, please try again if this problem persists, contact 'hello@kudobuzz.com'");
				}
			}
		});
	}

	/*
	 * Create wordpress account
	 */
	function create_word_account(email, url, account_name, password) {

		var mydata = {
			'email': email,
			'account_name': account_name,
			'url': url,
			'password': password,
			'platform': 'wordpress',
			'action': 'post_create_account_action'
		};

		jQuery.ajax({
			type: "POST",
			url: ajaxurl,
			data: mydata,
			dataType : "json",
			error: function (data) {
				console.log(data);
			},
			success: function (data, textStatus, jqXHR) {
				console.log(data);
				if (data == 1) {
					jQuery("#form-li a").removeAttr('data-toggle');
					window.location.href = "admin.php?page=Success";
					jQuery(".are_you_new").addClass("hide");
				} else if(data == 0)  {
					jQuery("#fb").css({'color': 'green'});
					jQuery("#fb").addClass('hide');
					jQuery("#create-user-btn").removeClass('hide');
					jQuery("input").prop("disabled", false);
					jQuery('#error').append("Something went wrong, please try again if this problem persists, contact 'hello@kudobuzz.com'");
				}
			}
		});
	}
</script>