<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package storefront
 */

get_header(); ?>
	<section id="slideshow" class="slideshow container-fluild hidden-xs">
		<div id="carousel-slideshow" class="carousel slide" data-ride="carousel">
			<div class="carousel-inner">
				<?php 
					$slide_first = 0;
					if( have_rows('slides', 'option') ){
						while( have_rows('slides', 'option')) : the_row();
						$slide_first = $slide_first + 1;
						$slide_link_active = get_sub_field('slide_active');
						$slide_link        = get_sub_field('slide_link');
						$slide_image       = get_sub_field('slide_image');
						$slide_description = get_sub_field('slide_description');

						$slide_content = sprintf(
							'<div class="item %5$s">
								%1$s
									<img src="%2$s" alt="%3$s" class="img-responsive" />
								%4$s	
							</div>',
							($slide_link_active == true ? sprintf('<a href="%s" title="%s">', $slide_link, $slide_description) : ''),
							$slide_image,
							$slide_description,
							($slide_link_active == true ? '</a>' : ''),
							($slide_first == 1 ? 'active' : '')
						);

						echo $slide_content;

						endwhile;
					}else {
						echo "Nenhum slideshow encontrado";
					}
				?>
			</div>
			<a class="left carousel-control" href="#carousel-slideshow" role="button" data-slide="prev">
			    <span class="icon-prev" aria-hidden="true"></span>
			    <span class="sr-only">Previous</span>
			</a>
			<a class="right carousel-control" href="#carousel-slideshow" role="button" data-slide="next">
			    <span class="icon-next" aria-hidden="true"></span>
			    <span class="sr-only">Next</span>
			</a>
		</div>	
	</section>
	<section class="slideshow-mobile container-fluild visible-xs-block">
		<div id="carousel-slideshow-mobile" class="carousel slide" data-ride="carousel">
			<div class="carousel-inner">
				<?php 
					if( have_rows('slides_mobile', 'option') ){
						while( have_rows('slides_mobile', 'option')) : the_row();

						$slide_link_active = get_sub_field('slide_active');
						$slide_link        = get_sub_field('slide_link');
						$slide_image       = get_sub_field('slide_image');
						$slide_description = get_sub_field('slide_description');

						$slide_content = sprintf(
							'<div class="main-slide">
								%1$s
									<img src="%2$s" alt="%3$s" class="img-responsive" />
								%4$s	
							</div>',
							($slide_link_active == true ? sprintf('<a href="%s" title="%s">', $slide_link, $slide_description) : ''),
							$slide_image,
							$slide_description,
							($slide_link_active == true ? '</a>' : '')
						);

						echo $slide_content;

						endwhile;
					}else {
						echo "";
					}
				?>
			</div>
			<a class="left carousel-control" href="#carousel-slideshow-mobile" role="button" data-slide="prev">
			    <span class="icon-prev" aria-hidden="true"></span>
			    <span class="sr-only">Previous</span>
			</a>
			<a class="right carousel-control" href="#carousel-slideshow-mobile" role="button" data-slide="next">
			    <span class="icon-next" aria-hidden="true"></span>
			    <span class="sr-only">Next</span>
			</a>
		</div>	
	</section>

	<div id="primary" class="content-area container">
		<main id="main" class="site-main" role="main">
			<div class="banners row">
				<div class="banner-home col-md-4">
					<img src="<?php the_field('first_featured', 'option'); ?>" />
				</div>
				<div class="banner-home col-md-4">
					<img src="<?php the_field('second_featured', 'option'); ?>" />
				</div>
				<div class="banner-home col-md-4">
					<img src="<?php the_field('third_featured', 'option'); ?>" />
				</div>
			</div>
			<div class="highlights row">
				<div class="col-md-7">
					<?php 
						if( have_rows('primary_banner', 'option') ){
							while( have_rows('primary_banner', 'option')) : the_row();

							$slide_link_active = get_sub_field('banner_primary_link_active');
							$slide_link        = get_sub_field('banner_primary_link');
							$slide_image       = get_sub_field('banner_primary_image');
							$slide_description = get_sub_field('banner_primary_description');

							$slide_content = sprintf(
								'<div class="primary-banner">
									%1$s
										<img src="%2$s" alt="%3$s" class="img-responsive" />
									%4$s	
								</div>',
								($slide_link_active == true ? sprintf('<a href="%s" title="%s">', $slide_link, $slide_description) : ''),
								$slide_image,
								$slide_description,
								($slide_link_active == true ? '</a>' : '')
							);

							echo $slide_content;

							endwhile;
						}else {
							echo "Nenhum slideshow encontrado";
						}
					?>
				</div>
				<div class="col-md-5">
					<?php 
						if( have_rows('first_banner_sidebar', 'option') ){
							while( have_rows('first_banner_sidebar', 'option')) : the_row();

							$slide_link_active = get_sub_field('banner_sidebar_first_link_active');
							$slide_link        = get_sub_field('banner_sidebar_first_link');
							$slide_image       = get_sub_field('banner_sidebar_first_image');
							$slide_description = get_sub_field('banner_sidebar_first_description');

							$slide_content = sprintf(
								'<div class="first-sidebar-banner">
									%1$s
										<img src="%2$s" alt="%3$s" class="img-responsive" />
									%4$s	
								</div>',
								($slide_link_active == true ? sprintf('<a href="%s" title="%s">', $slide_link, $slide_description) : ''),
								$slide_image,
								$slide_description,
								($slide_link_active == true ? '</a>' : '')
							);

							echo $slide_content;

							endwhile;
						}else {
							echo "Nenhum slideshow encontrado";
						}
					?>
					<?php 
						if( have_rows('second_banner_sidebar', 'option') ){
							while( have_rows('second_banner_sidebar', 'option')) : the_row();

							$slide_link_active = get_sub_field('banner_sidebar_second_link_active');
							$slide_link        = get_sub_field('banner_sidebar_second_link');
							$slide_image       = get_sub_field('banner_sidebar_second_image');
							$slide_description = get_sub_field('banner_sidebar_second_description');

							$slide_content = sprintf(
								'<div class="second-sidebar-banner">
									%1$s
										<img src="%2$s" alt="%3$s" class="img-responsive" />
									%4$s	
								</div>',
								($slide_link_active == true ? sprintf('<a href="%s" title="%s">', $slide_link, $slide_description) : ''),
								$slide_image,
								$slide_description,
								($slide_link_active == true ? '</a>' : '')
							);

							echo $slide_content;

							endwhile;
						}else {
							echo "Nenhum slideshow encontrado";
						}
					?>
					<?php 
						if( have_rows('third_banner_sidebar', 'option') ){
							while( have_rows('third_banner_sidebar', 'option')) : the_row();

							$slide_link_active = get_sub_field('banner_sidebar_third_link_active');
							$slide_link        = get_sub_field('banner_sidebar_third_link');
							$slide_image       = get_sub_field('banner_sidebar_third_image');
							$slide_description = get_sub_field('banner_sidebar_third_description');

							$slide_content = sprintf(
								'<div class="third-sidebar-banner">
									%1$s
										<img src="%2$s" alt="%3$s" class="img-responsive" />
									%4$s	
								</div>',
								($slide_link_active == true ? sprintf('<a href="%s" title="%s">', $slide_link, $slide_description) : ''),
								$slide_image,
								$slide_description,
								($slide_link_active == true ? '</a>' : '')
							);

							echo $slide_content;

							endwhile;
						}else {
							echo "Nenhum slideshow encontrado";
						}
					?>
				</div>
			</div>
			<div class="featured-products carousel-products row">
				<div class="col-md-12">		
					<h1 class="title-carousel-products title-product-recents">Novidades <span class="navigation-carousel"></span></h1>
					<?php echo do_shortcode('[featured_products per_page="5" columns="4"]');?>
				</div>
			</div>
			<div class="recent-products carousel-products sale-carousel row">
				<div class="col-md-12">		
					<h1 class="title-carousel-products title-product-recents title-blue">+ Vendidos <span class="navigation-carousel"></span></h1>
					<?php echo do_shortcode('[featured_products per_page="5" columns="4"]');?>
				</div>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->
	
	<div class="newsletters">
		<div class="container">
			<div class="row">
				<div class="col--md-12">
				<?php gravity_form( 1, $display_title = false, $display_description = false, $display_inactive = false, $field_values = null, $ajax = false, $tabindex, $echo = true ); ?>
				</div>
			</div>
		</div>
	</div>
<?php
get_footer();