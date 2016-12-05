<?php 
  $template_url = get_template_directory_uri()."-child";


?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">
		<link rel="profile" href="http://gmpg.org/xfn/11">
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

		<?php wp_head(); 


		?>
	</head>
	<body <?php body_class(); ?>>



		<header class="main-header">
		   	<div class="top-header hidden-xs">
		   	    <div class="container">
			   	    <div class="row">
				   	    <div class="col-md-3">
							<div class="info-header">
								<a class="logo-header" href="<?php echo site_url(); ?>" title="Lallupe">
									<img src="<?php echo $template_url; ?>/app/images/header-logo.png" class="logo-mobile"/>
								</a>
							</div>
						</div>
						<div class="col-md-9" data-active="false">	
							<div class="row">
								<div class="col-md-10">
									<div class="search-header">
										<?php dynamic_sidebar( 'search-header' ); ?>
									</div>
								</div>
								<div class="col-md-2">
									<?php do_action('storefront_child_cart_header'); ?>
								</div>
							</div>
							<div class="navbar navbar-default">
						    	<div class="navbar-header">
								    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu-primary" aria-expanded="false">
								        <span class="sr-only">Toggle navigation</span>
								        <span class="icon-bar icon-top"></span>
								        <span class="icon-bar icon-hide"></span>
								        <span class="icon-bar icon-bottom"></span>
								    </button>
							    </div>  
						        <div class="collapse navbar-collapse" id="menu-primary">
									<?php wp_nav_menu(array(
						                'container'       => false,
						                'items_wrap'      => '<ul id="%1$s" class="%2$s nav navbar-nav">%3$s</ul>',
						                'walker'          => new twitter_bootstrap_nav_walker,
						                'theme_location'	=> 'primary',
						            ));?>
						        </div>
						        <ul class="social-header">
										<li class="item-social-header">
											<a class="twitter-header" href="https://twitter.com/lallupeoficial" title="Twitter" target="_BLANK">
												<i class="sprite icon-header-twitter"></i>
											</a>
										</li>
										<li class="item-social-header">
											<a class="facebook-header" href="https://www.facebook.com/lallupe/" title="Facebook" target="_BLANK">
												<i class="sprite icon-header-facebook"></i>
											</a>
										</li>
										<li class="item-social-header">
											<a class="instagran-header" href="https://www.instagram.com/lallupeoficial/" title="Twitter" target="_BLANK">
												<i class="sprite icon-header-instagran"></i>
											</a>
										</li>
									</ul>													
						    </div>	
						</div>
					</div>
					<div class="row">
						<div class="col-md-9 col-xs-2 line-right-mobile">
																				
						</div>
						<div class="col-md-1">
							<ul class="shop-header">
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="middle-header hidden-xs">
			    <div class="container">
				    
				</div>
			</div>
			<div class="bottom-header container">
				<div class="row">

					<div class="col-md-9 col-xs-2 line-right-mobile hidden-md hidden-lg">
						<div class="navbar navbar-default">
					    	<div class="navbar-header">
							    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu-primary-mobile" aria-expanded="false">
							        <span class="sr-only">Toggle navigation</span>
							        <span class="icon-bar icon-top"></span>
							        <span class="icon-bar icon-hide"></span>
							        <span class="icon-bar icon-bottom"></span>
							    </button>
						    </div>  
					        <div class="collapse navbar-collapse" id="menu-primary-mobile">
								<?php wp_nav_menu(array(
					                'container'       => false,
					                'items_wrap'      => '<ul id="%1$s" class="%2$s nav navbar-nav">%3$s</ul>',
					                'walker'          => new twitter_bootstrap_nav_walker,
					                'theme_location'	=> 'primary',
					            ));?>
					        </div>													
					    </div>														
					</div>
					<div class="col-md-3 col-md-offset-4 hidden-xs hidden-md hidden-lg search-header-container" data-active="false">	
						<div class="search-header">
							<?php dynamic_sidebar( 'search-header' ); ?>
						</div>
					</div>
					<div class="col-xs-6 line-right-mobile visible-xs-block">
						
					</div>
					<div class="col-xs-2 line-right-mobile menu-container-mobile visible-xs-block">
						<a href="#" class="icon-search-mobile-header search-mobile">
							<i class="fa fa-search fa-2x" aria-hidden="true"></i>
						</a>
					</div>
					<div class="col-xs-2  visible-xs-block">
						<a href="<?php echo site_url('/carrinho/');?>" class="icon-cart-mobile-header" style="margin-top:5px;">
							<i class="fa fa-shopping-cart fa-2x" aria-hidden="true"></i>
						</a>
					</div>
					
					
				</div>
			</div>
		<hr class="divider-header"/>	
		</header>
