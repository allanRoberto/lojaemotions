<?php
/**
 * Template Name: Duvidas frequentes		
 *
 * @package storefront
 */

get_header(); ?>

	<div id="primary" class="content-area container">
		<h1 class="title-primary" style="margin-top: 60px;">Precisa de uma ajudinha ?</h1>
		<main id="main" class="site-main questions-template" role="main">
			<div class="row">
				<div class="col-md-4">
					<?php
					 	wp_nav_menu(
							array(
								'theme_location'	=> 'sidebar-menu',
								'container_class'	=> 'woocommerce-MyAccount-navigation sidebar-menu',
								'container'         => 'nav',
							)
						);
					?>
				</div>
				<div class="col-md-8">

					<?php while ( have_posts() ) : the_post();

						the_content();


					endwhile; // End of the loop. ?>
				</div>
			</div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();