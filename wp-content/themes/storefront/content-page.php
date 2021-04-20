<?php

/**
 * The template used for displaying page content in page.php
 *
 * @package storefront
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<h1>content-page.php</h1>
	<?php
	/**
	 * Functions hooked in to storefront_page add_action
	 *
	 * @hooked storefront_page_header          - 10
	 * @hooked storefront_page_content         - 20
	 */
	do_action('storefront_page');
	?>
</article><!-- #post-## -->