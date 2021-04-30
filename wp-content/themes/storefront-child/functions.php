<?php

add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');
function my_theme_enqueue_styles()
{
	wp_enqueue_style(
		'child-style',
		get_stylesheet_uri(),
		array('storefront-style'),
		wp_get_theme()->get('Version') // this only works if you have Version in the style header
	);
}

remove_action('woocommerce_widget_shopping_cart_total', 'woocommerce_widget_shopping_cart_subtotal');
remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart');
remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20);

/**
 * Output the view cart button.
 */
function woo_btn_view_cart()
{
	echo '<a href="' . esc_url(wc_get_cart_url()) . '" class="button wc-forward">' . esc_html__('Lista de desejos', 'woocommerce') . '</a>';
}


/**
 * Output the proceed to checkout button.
 */
function woo_btn_checkout()
{
	echo '<a href="' . esc_url(wc_get_checkout_url()) . '" class="button checkout wc-forward">' . esc_html__('Orçamento', 'woocommerce') . '</a>';
}

add_action('woocommerce_widget_shopping_cart_buttons', 'woo_btn_view_cart');
add_action('woocommerce_widget_shopping_cart_buttons', 'woo_btn_checkout', 20);
