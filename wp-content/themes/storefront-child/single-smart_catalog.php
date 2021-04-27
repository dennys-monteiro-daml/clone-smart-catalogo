<?php

/**
 * The template for displaying all single posts.
 *
 * @package storefront
 */

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main catalog-page" role="main">
		<!-- <h1>EPA</h1> -->
		<?php
		while (have_posts()) :
			the_post();

			do_action('storefront_single_post_before');

			get_template_part('content', 'single');

			$id = get_the_ID();

			// consulta os produtos do catálogo
			$query = new WP_Query(array(
				'post_type' => 'product',
				'meta_query' => array(
					array(
						'key' => 'catalog_id',
						'value' => $id,
						'compare' => '='
					)
				),
			));
			$products = array();

			if ($query->have_posts()) {
				// echo '<p>Produtos no catálogo: </p>';
				// echo '<ul>';
				while ($query->have_posts()) {
					$query->the_post();
					$product_id = $query->post->ID;

					// montar array dos produtos
					$products[] = array(
						'title' => $query->post->post_title,
						'url' => get_permalink($product_id),
						'catalog_page' => get_post_meta($product_id, 'catalog_page', true),
						'cropped' => json_decode(get_post_meta($product_id, 'cropped', true)),
						'id' => $product_id,
					);
					// echo '<li>' . get_the_ID() . '- ' . get_the_title() . '</li>';
				}
				// echo '</ul>';
			}

			wp_reset_postdata();

			$number_of_pages = get_post_meta($id, 'number_of_pages', true);

			if (!empty($number_of_pages) && intval($number_of_pages) > 0) {
				$number_of_pages = intval($number_of_pages);
				// loop nas paginas do catalogo
				for ($i = 0; $i < $number_of_pages; $i++) {

					// verificar produtos nesta pagina do catalogo
					$products_on_page = array_filter($products, function ($product) use ($i) {
						return $product['catalog_page'] == $i;
					});

					// exibe os links dos produtos
					foreach ($products_on_page as $product) { ?>
						<div style="position:absolute;" id="product-<?php echo $product['id'] ?>">
							<a href="<?php echo $product['url'] ?>" target="_blank">
								<?php echo $product['title'] ?>
								<i class="fas fa-external-link-alt"></i>
							</a>
						</div>

					<?php } ?>

					<img id="catalog-page-<?php echo $i ?>" src="<?php echo Pdf_To_Woocommerce_Admin::get_upload_url($id)
																		. Pdf_To_Woocommerce_Admin::PDF_CONVERTED_FOLDER
																		. DIRECTORY_SEPARATOR
																		. "$i.png" ?>" class="img-fluid" />

		<?php }
			}
			do_action('storefront_single_post_after', $products);
		endwhile; // End of the loop. 
		?>

	</main><!-- #main -->
</div><!-- #primary -->

<?php
do_action('storefront_sidebar');
get_footer();
