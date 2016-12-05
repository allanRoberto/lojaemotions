<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package storefront
 */

?>

		</div><!-- .col-full -->
	</div><!-- #content -->

	<?php do_action( 'storefront_before_footer' ); ?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="col-full">

			<?php
			/**
			 * Functions hooked in to storefront_footer action
			 *
			 * @hooked storefront_footer_widgets - 10
			 * @hooked storefront_credit         - 20
			 */
			do_action( 'storefront_footer' ); ?>

		</div><!-- .col-full -->
	</footer><!-- #colophon -->
    <div class="copyright">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<h4>CNPJ 25.308.764/0001-70 | www.lallupe.com.br | TODOS OS DIREITOS RESERVADOS | Caixa Postal 78475 São Paulo - SP CEP 01401-970</h4>
					<h4>As fotos aqui veiculadas, logotipos e marca são de propriedade do site. Desenvolvimento <a href="http://saintpaulcomunicacao.com.br" target="_BLANK">Saint Paul Comunicação</a>
				</div>
			</div>
		</div>
    </div>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
