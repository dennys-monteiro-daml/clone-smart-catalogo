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
		// https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css
		wp_enqueue_style($this->plugin_name . 'jquery-modal', "https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css", array(), $this->version, 'all');
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/pdf-to-woocommerce-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{
		// https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js
		wp_enqueue_script($this->plugin_name . 'jquery-modal', "https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js", array('jquery'), $this->version, false);
		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/pdf-to-woocommerce-public.js', array('jquery'), $this->version, false);
	}


	public function register_post_types()
	{
		$sc = Smart_Catalog::get_instance();
		$sc->register_post_type();
		$sc->register_post_status();

		$fabricantes = Fabricante::get_instance();
		$fabricantes->register_post_type();
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

	public function add_post_type_metabox()
	{
		add_meta_box('study_meta', 'Study Details', function () {
			include_once(plugin_dir_path(dirname(__FILE__)) . 'admin/views/form-upload-catalog.php');
		});
	}

	public function display_post_states($states)
	{
		global $post;
		$arg = get_query_var('post_status');
		if ($arg != 'uploaded') {
			if (isset($post) && $post->post_status == 'uploaded') {
				return array('Em demarcação');
			}
		}
		return $states;
	}

	function jc_append_post_status_list()
	{
		global $post;
		$complete = '';
		$label = '';
		if ($post->post_type == Smart_Catalog::get_instance()->post_type) {
			if ($post->post_status == 'uploaded') {
				$complete = ' selected=\"selected\"';
				$label = 'Em demarcação';
			}
			echo '
			 <script>
			 jQuery(document).ready(function($){
				  $("select#post_status").append("<option value=\"uploaded\" ' . $complete . '>Em demarcação</option>");
				  $("#post-status-display").text("' . $label . '");
			 });
			 </script>
			 ';
		}
	}

	function kinsta_books_on_blog_page($query)
	{
		if ($query->is_home() && $query->is_main_query()) {
			$query->set('post_type', array('post', 'smart_catalog'));
		}
	}

	function add_script_to_catalog($products = array())
	{
		if (!get_post_type(get_the_ID()) === Smart_Catalog::get_instance()->post_type) {
			return;
		}

		$handle = $this->plugin_name . '-products';
		wp_enqueue_script($handle, plugin_dir_url(__FILE__) . 'js/products.js', array('jquery'), $this->version, false);
		wp_localize_script($handle, 'wp_products', array(
			'products' => $products
		));
	}
	
}
