<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       brunopolo.com.br
 * @since      1.0.0
 *
 * @package    Pdf_To_Woocommerce
 * @subpackage Pdf_To_Woocommerce/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Pdf_To_Woocommerce
 * @subpackage Pdf_To_Woocommerce/public
 * @author     Bruno Polo <brunopolo@poli.ufrj.br>
 */
class Pdf_To_Woocommerce_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pdf_To_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pdf_To_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/pdf-to-woocommerce-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Pdf_To_Woocommerce_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Pdf_To_Woocommerce_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/pdf-to-woocommerce-public.js', array('jquery'), $this->version, false);
	}


	public function register_post_types()
	{
		$sc = Smart_Catalog::get_instance();
		$sc->register_post_type();

		// register_post_type(
		// 	'smart_catalog',
		// 	// CPT Options
		// 	array(
		// 		'labels' => xcompile_post_type_labels('Catálogo', 'Catálogos'),
		// 		'public' => true,
		// 		'has_archive' => true,
		// 		'rewrite' => array('slug' => 'catalogo'),
		// 		'show_in_rest' => true,
		// 		'supports' => array(
		// 			'title'
		// 		),
		// 		'register_meta_box_cb' => array($this, 'add_post_type_metabox'),
		// 		'menu_icon' => 'dashicons-analytics'

		// 	)
		// );
	}

	public function remove_gutemberg($enabled, $post_type)
	{

		// List of post types to remove
		$remove_gutenberg_from = ['smart_catalog'];

		if (in_array($post_type, $remove_gutenberg_from)) {
			return false;
		}

		return $enabled;
	}

	public function add_post_type_metabox(WP_Post $post)
	{
		add_meta_box('study_meta', 'Study Details', function () use ($post) {
			include_once(plugin_dir_path(dirname(__FILE__)) . 'admin/views/form-upload-catalog.php');
		});
	}
}
