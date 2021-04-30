<?php

/**
 * Template used to display post content.
 *
 * @package storefront
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php

	$post_type = get_post_type(get_the_ID());

	if ($post_type === Smart_Catalog::get_instance()->post_type) { ?>
		<div style="display: flex;">
			<div style="width: 33%;">
				<img src='<?php echo Pdf_To_Woocommerce_Admin::get_upload_url(get_the_ID()) . Pdf_To_Woocommerce_Admin::PDF_CONVERTED_FOLDER ?>/0.png' />
			</div>
			<div style="margin-left: 20px; width: 66%;">
				<?php do_action('storefront_loop_post'); ?>
			</div>
		</div>


	<?php } else {

		/**
		 * Functions hooked in to storefront_loop_post action.
		 *
		 * @hooked storefront_post_header          - 10
		 * @hooked storefront_post_content         - 30
		 * @hooked storefront_post_taxonomy        - 40
		 */
		do_action('storefront_loop_post');
	}

	?>

</article><!-- #post-## -->