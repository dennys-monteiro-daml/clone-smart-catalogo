<?php

/**
 * Template used to display post content.
 *
 * @package storefront
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<h1>content.php</h1>
	<?php
	// $posts = new Post_Data();
	// /** @var Post_Data */
	// $posts = $posts->findById(get_the_ID());
	// $posts = $posts->fetch_post_meta();
	// echo "<pre>";
	// print_r($posts);
	// echo "</pre>";
	$id = get_the_ID();
	$number_of_pages = get_post_meta($id, 'number_of_pages', true);
	if (!empty($number_of_pages) && intval($number_of_pages) > 0) {
		$number_of_pages = intval($number_of_pages);
		for ($i = 0; $i < $number_of_pages; $i++) {
			?>
			<img src="<?php echo Pdf_To_Woocommerce_Admin::get_upload_url($id)
                            . Pdf_To_Woocommerce_Admin::PDF_CONVERTED_FOLDER
                            . DIRECTORY_SEPARATOR
                            . "$i.png" ?>" class="img-fluid" />
			<?php
		}
	}

	/**
	 * Functions hooked in to storefront_loop_post action.
	 *
	 * @hooked storefront_post_header          - 10
	 * @hooked storefront_post_content         - 30
	 * @hooked storefront_post_taxonomy        - 40
	 */
	do_action('storefront_loop_post');
	?>

</article><!-- #post-## -->