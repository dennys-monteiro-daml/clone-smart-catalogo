<?php

use Spatie\PdfToImage\Pdf;

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       brunopolo.com.br
 * @since      1.0.0
 *
 * @package    Pdf_To_Woocommerce
 * @subpackage Pdf_To_Woocommerce/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pdf_To_Woocommerce
 * @subpackage Pdf_To_Woocommerce/admin
 * @author     Bruno Polo <brunopolo@poli.ufrj.br>
 */
class Pdf_To_Woocommerce_Admin
{


	public const ADMIN_MENU_ITEMS = [
		[
			'page_title' => 'Importar Catálogos',
			'menu_title' => 'Importar Catálogos',
			'capability' => 'administrator',
			'slug' => 'page-slug',
			'callback' => 'page_main',
			'icon' => 'dashicons-analytics',
			'position' => 0
		]
	];

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

	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/pdf-to-woocommerce-admin.css', array(), $this->version, 'all');
	}

	public function enqueue_scripts()
	{
		// wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . '/js/src/index.bundle.js', array('jquery'), $this->version, false);
		// wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/pdf-to-woocommerce-admin.js', array('jquery'), $this->version, false);
	}

	public function admin_menu()
	{

		// $menus = Pdf_To_Woocommerce_Admin::ADMIN_MENU_ITEMS;

		// foreach ($menus as $menu) {
		// 	add_menu_page(
		// 		$menu['page_title'],
		// 		$menu['menu_title'],
		// 		$menu['capability'],
		// 		$menu['slug'],
		// 		array($this, $menu['callback']),
		// 		$menu['icon'],
		// 		$menu['position']
		// 	);
		// }
	}

	public function page_main()
	{
		include_once('views/form-upload-catalog.php');
		// $location = "C:/xampp/htdocs/galeria021/wp-content/plugins/pdf-to-woocommerce/pdf/Produto05_Outubro20.pdf";//plugin_dir_path(dirname(__FILE__)) . 'pdf' . DIRECTORY_SEPARATOR . 'Produto05_Outubro20.pdf';
		// echo "<pre>";
		// echo $location . "\n";
		// print_r(get_loaded_extensions());
		// $pdf = new Pdf($location);

		// $pdf->setOutputFormat('png')->saveImage('C:\\test.png');
		// echo "</pre>";
		// echo "<h1>Olá mundo!</h1>";
	}
}
