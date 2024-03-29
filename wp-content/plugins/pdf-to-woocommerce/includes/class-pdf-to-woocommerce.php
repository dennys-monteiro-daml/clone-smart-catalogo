<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       brunopolo.com.br
 * @since      1.0.0
 *
 * @package    Pdf_To_Woocommerce
 * @subpackage Pdf_To_Woocommerce/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Pdf_To_Woocommerce
 * @subpackage Pdf_To_Woocommerce/includes
 * @author     Bruno Polo <brunopolo@poli.ufrj.br>
 */
class Pdf_To_Woocommerce
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Pdf_To_Woocommerce_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('PDF_TO_WOOCOMMERCE_VERSION')) {
			$this->version = PDF_TO_WOOCOMMERCE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'pdf-to-woocommerce';

		$this->load_dependencies();
		$this->set_locale();

		remove_action('woocommerce_widget_shopping_cart_total', 'woocommerce_widget_shopping_cart_subtotal');
		remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart');
		remove_action('woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20);
		
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Pdf_To_Woocommerce_Loader. Orchestrates the hooks of the plugin.
	 * - Pdf_To_Woocommerce_i18n. Defines internationalization functionality.
	 * - Pdf_To_Woocommerce_Admin. Defines all hooks for the admin area.
	 * - Pdf_To_Woocommerce_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * External packages
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'composer/vendor/autoload.php';

		/**
		 * Utils functions 
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'utils/functions.php';

		/**
		 * The Post_Data and Post_Meta_Data classes
		 */
		// require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-post-data.php';
		// require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-post-meta-data.php';

		/**
		 * The Models
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'models/_loader.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-pdf-to-woocommerce-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-pdf-to-woocommerce-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-pdf-to-woocommerce-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-pdf-to-woocommerce-public.php';

		$this->loader = new Pdf_To_Woocommerce_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Pdf_To_Woocommerce_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Pdf_To_Woocommerce_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Pdf_To_Woocommerce_Admin($this->get_plugin_name(), $this->get_version());

		$sc = Smart_Catalog::get_instance();

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
		$this->loader->add_filter('use_block_editor_for_post_type', $plugin_admin, 'remove_gutemberg', 10, 2);
		$this->loader->add_action('wp_ajax_add_pdf', $plugin_admin, 'handle_pdf_upload');
		$this->loader->add_action('wp_ajax_convert_pdf', $plugin_admin, 'convert_pdf_page');
		$this->loader->add_action('wp_ajax_create_product', $plugin_admin, 'create_product');
		$this->loader->add_action('script_loader_tag', $plugin_admin, 'script_loader_tag', 10, 3);

		$this->loader->add_action('save_post_' . $sc->post_type, $sc, 'on_post_saved', 10, 2);
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$plugin_public = new Pdf_To_Woocommerce_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('init', $plugin_public, 'register_post_types');

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
		$this->loader->add_filter('display_post_states', $plugin_public, 'display_post_states');

		$this->loader->add_filter('woocommerce_get_price_html', $plugin_public, 'hide_free_price_notice');
		// $this->loader->add_filter('woocommerce_variable_free_price_html', $plugin_public, 'hide_free_price_notice');
		// $this->loader->add_filter('woocommerce_variation_free_price_html', $plugin_public, 'hide_free_price_notice');

		$this->loader->add_filter('woocommerce_product_single_add_to_cart_text', $plugin_public, 'woo_custom_cart_button_text');
		$this->loader->add_filter('woocommerce_product_add_to_cart_text', $plugin_public, 'woo_custom_cart_button_text');

		$this->loader->add_filter('woocommerce_order_button_text', $plugin_public, 'woo_order_button_text');

		$this->loader->add_filter('woocommerce_thankyou_order_received_text', $plugin_public, 'woo_thankyou_text');

		$this->loader->add_action('admin_footer-post.php', $plugin_public, 'jc_append_post_status_list');
		// $this->loader->add_action('pre_get_posts', $plugin_public, 'kinsta_books_on_blog_page');
		//$this->loader->add_action('storefront_loop_post', $plugin_public, 'storefront_loop_post');
		$this->loader->add_action('storefront_single_post_after', $plugin_public, 'add_script_to_catalog');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Pdf_To_Woocommerce_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}
