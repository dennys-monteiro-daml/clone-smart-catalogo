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

	public const PDF_CONVERTED_FOLDER = 'converted';

	public static function get_upload_dir($post_id)
	{
		return implode(DIRECTORY_SEPARATOR, array(
			untrailingslashit(plugin_dir_path(__FILE__)),
			'uploads',
			$post_id
		)) . DIRECTORY_SEPARATOR;
	}

	public static function get_upload_url($post_id)
	{
		return implode(DIRECTORY_SEPARATOR, array(
			untrailingslashit(plugin_dir_url(__FILE__)),
			'uploads',
			$post_id
		)) . DIRECTORY_SEPARATOR;
	}
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
		wp_enqueue_script($this->plugin_name . "-admin", plugin_dir_url(__FILE__) . 'js/admin.js', array('jquery'), $this->version, false);
		wp_localize_script($this->plugin_name . "-admin", "wp_object", array(
			"site_url" => get_site_url(),
			"admin_url" => get_admin_url()
		));
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

	public function remove_gutemberg($enabled, $post_type)
	{

		// List of post types to remove
		$remove_gutenberg_from = ['smart_catalog'];

		if (in_array($post_type, $remove_gutenberg_from)) {
			return false;
		}

		return $enabled;
	}

	public function handle_pdf_upload()
	{
		try {

			if (!isset($_POST['add_pdf_nonce']) || !wp_verify_nonce($_POST['add_pdf_nonce'], 'add_pdf_nonce')) {
				echo json_encode(array(
					'status' => 'error',
					'message' => 'Não autorizado'
				), JSON_UNESCAPED_UNICODE);
				die();
			}

			if (empty($_FILES) || !isset($_POST['post_ID'])) {
				echo json_encode(array(
					'status' => 'error',
					'message' => 'Dados insuficientes'
				), JSON_UNESCAPED_UNICODE);
				die();
			}

			$uploaddir = Pdf_To_Woocommerce_Admin::get_upload_dir($_POST['post_ID']);

			if (!is_dir($uploaddir)) {
				mkdir($uploaddir, 0777, true);
				mkdir($uploaddir . DIRECTORY_SEPARATOR . Pdf_To_Woocommerce_Admin::PDF_CONVERTED_FOLDER, 0777, true);
			}

			$uploadfile = implode(DIRECTORY_SEPARATOR, array(
				untrailingslashit($uploaddir),
				basename($_FILES['file']['name'])
			));

			if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {

				$pdf = new Pdf($uploadfile);
				$page_count = $pdf->getNumberOfPages();

				update_post_meta($_POST['post_ID'], Smart_Catalog::META_KEY_NUMBER_OF_PAGES, $page_count);
				wp_update_post(array(
					'ID' => $_POST['post_ID'],
					'post_status' => 'uploaded'
				));

				for ($i = 0; $i < $page_count; $i++) {
					$pdf->setPage($i + 1)
						->saveImage(implode(DIRECTORY_SEPARATOR, array(
							untrailingslashit($uploaddir),
							Pdf_To_Woocommerce_Admin::PDF_CONVERTED_FOLDER,
							"$i.png"
						)));
				}


				echo json_encode(array(
					'status' => 'ok',
					'message' => 'Arquivo recebido',
					'files' => $_FILES
				), JSON_UNESCAPED_UNICODE);
				die();
			} else {

				echo json_encode(array(
					'status' => 'error',
					'message' => 'Erro ao salvar o arquivo'
				), JSON_UNESCAPED_UNICODE);
				die();
			}
		} catch (\Exception $error) {

			echo json_encode(array(
				'status' => 'error',
				'message' => 'Erro ao salvar o arquivo',
				'error' => $error
			), JSON_UNESCAPED_UNICODE);
			die();
		}
	}

	public function save_catalogo($post_id)
	{
		$post = get_post($post_id);
		$is_revision = wp_is_post_revision($post_id);
		// $field_name = 'file';
		// Do not save meta for a revision or on autosave
		if ($post->post_type != Smart_Catalog::get_instance()->post_type || $is_revision)
			return;

		// Do not change if post is published OR uploaded
		if ($post->post_status === 'published' || $post->post_status === 'uploaded')
			return;

		$number_of_pages = get_post_meta($post_id, Smart_Catalog::META_KEY_NUMBER_OF_PAGES, true);

		if ($number_of_pages != '' && intval($number_of_pages) > 0) {

			wp_update_post(array(
				'ID' => $post_id,
				'post_status' => 'uploaded'
			));
		}


		// $post = get_post($post_id);
		// $is_revision = wp_is_post_revision($post_id);
		// $field_name = 'file';



		// // Secure with nonce field check
		// if (!check_admin_referer('add_pdf_nonce', 'add_pdf_nonce'))
		// 	return;

		// if (!empty($_FILES)) {

		// 	$uploaddir = plugin_dir_path(__FILE__) . 'uploads/';
		// 	$uploadfile = $uploaddir . basename($_FILES['file']['name']);

		// 	if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadfile)) {
		// 		echo "Arquivo válido e enviado com sucesso.\n";
		// 	} else {
		// 		trigger_error("Erro ao enviar o arquivo");
		// 	}
		// }
	}
}
