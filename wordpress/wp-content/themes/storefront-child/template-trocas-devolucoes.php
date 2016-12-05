<?php
/**
 * Template Name: Trocas		
 *
 * @package storefront
 */

get_header(); ?>

	<div id="primary" class="content-area container">
		<main id="main" class="site-main devolucoes-template" role="main">

			<div class="row">
				<div class="col-md-2">
					<?php
					 	wp_nav_menu(
							array(
								'theme_location'	=> 'sidebar-menu',
								'container_class'	=> 'woocommerce-MyAccount-navigation',
								'container'         => 'nav',
							)
						);
					?>
				</div>
				<div class="col-md-10">

					<?php while ( have_posts() ) : the_post();

						the_content();


					endwhile; // End of the loop. ?>
				</div>
			</div>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();