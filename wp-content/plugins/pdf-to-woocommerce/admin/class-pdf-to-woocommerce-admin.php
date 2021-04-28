<?php

use Bnb\PdfToImage\Pdf;

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

	public function script_loader_tag($tag, $handle, $src)
	{
		// if not your script, do nothing and return original $tag
		if ($this->plugin_name . "-icons" !== $handle) {
			return $tag;
		}
		// change the script tag by adding type="module" and return it.
		$tag = '<script type="module" src="' . esc_url($src) . '"></script>';
		return $tag;
	}

	public function enqueue_styles()
	{
		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/pdf-to-woocommerce-admin.css', array(), $this->version, 'all');
	}

	public function enqueue_scripts()
	{

		// load all node_modules
		require_once "js/lib/_loader.php";

		wp_enqueue_script($this->plugin_name . "-icons", plugin_dir_url(__FILE__) . 'js/icons.js', array(), $this->version, false);

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/admin.js', array('jquery'), $this->version, false);
		wp_localize_script($this->plugin_name, "wp_object", array(
			"site_url" => get_site_url(),
			"admin_url" => get_admin_url(),
			"convert_pdf_nonce" => wp_create_nonce('convert_pdf_nonce'),
			"plugin_admin_url" => plugin_dir_url(__FILE__),
			"converted_folder" => Pdf_To_Woocommerce_Admin::PDF_CONVERTED_FOLDER,
			"product_cat" => get_woocommerce_categories_options()
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

				// $pdf = new Pdf($uploadfile);
				// $page_count = $pdf->getNumberOfPages();

				// update_post_meta($_POST['post_ID'], Smart_Catalog::META_KEY_NUMBER_OF_PAGES, $page_count);
				// wp_update_post(array(
				// 	'ID' => $_POST['post_ID'],
				// 	'post_status' => 'uploaded'
				// ));

				// for ($i = 0; $i < $page_count; $i++) {
				// 	$pdf->setPage($i + 1)
				// 		->saveImage(implode(DIRECTORY_SEPARATOR, array(
				// 			untrailingslashit($uploaddir),
				// 			Pdf_To_Woocommerce_Admin::PDF_CONVERTED_FOLDER,
				// 			"$i.png"
				// 		)));
				// }
				$pdf = new Pdf($uploadfile);
				$page_count = $pdf->getNumberOfPages();



				echo json_encode(array(
					'status' => 'ok',
					'message' => 'Arquivo recebido',
					'pdf_location' => $uploadfile,
					'pages' => $page_count,
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

	public function convert_pdf_page()
	{
		try {

			if (!isset($_POST['convert_pdf_nonce']) || !wp_verify_nonce($_POST['convert_pdf_nonce'], 'convert_pdf_nonce')) {
				echo json_encode(array(
					'status' => 'error',
					'message' => 'Não autorizado'
				), JSON_UNESCAPED_UNICODE);
				die();
			}

			if (!isset($_POST['page']) || !isset($_POST['post_ID']) || !isset($_POST['pdf_location'])) {
				echo json_encode(array(
					'status' => 'error',
					'message' => 'Dados insuficientes'
				), JSON_UNESCAPED_UNICODE);
				die();
			}
			$uploadfile = $_POST['pdf_location'];
			$i = $_POST['page'];

			$pdf = new Pdf($uploadfile);
			$page_count = $pdf->getNumberOfPages();

			if ($i >= $page_count || $i < 0) {
				echo json_encode(array(
					'status' => 'error',
					'message' => 'Página solicitada não existe'
				), JSON_UNESCAPED_UNICODE);
				die();
			}

			$uploaddir = self::get_upload_dir($_POST['post_ID']);

			// for ($i = 0; $i < $page_count; $i++) {
			$pdf->setPage($i + 1)
				->saveImage(implode(DIRECTORY_SEPARATOR, array(
					untrailingslashit($uploaddir),
					self::PDF_CONVERTED_FOLDER,
					"$i.png"
				)));
			// }
			if ($page_count == $i + 1) {
				update_post_meta($_POST['post_ID'], Smart_Catalog::META_KEY_NUMBER_OF_PAGES, $page_count);
				wp_update_post(array(
					'ID' => $_POST['post_ID'],
					'post_status' => 'uploaded'
				));
			}

			echo json_encode(array(
				'status' => 'ok',
				'message' => 'Arquivo recebido',

			), JSON_UNESCAPED_UNICODE);
			die();
		} catch (\Exception $error) {

			echo json_encode(array(
				'status' => 'error',
				'message' => 'Erro ao salvar o arquivo',
				'error' => $error
			), JSON_UNESCAPED_UNICODE);
			die();
		}
	}

	public function create_product()
	{
		try {

			// crop image and add to WP upload folder

			$wordpress_upload_dir = wp_upload_dir();
			$page_file_path = implode(DIRECTORY_SEPARATOR, array(
				untrailingslashit(self::get_upload_dir($_POST['catalog_id'])),
				self::PDF_CONVERTED_FOLDER,
				$_POST['catalog_page'] . ".png"
			)); // self::get_upload_dir($_POST['catalog_id']) . ;

			$imagick = new Imagick(realpath($page_file_path));

			$new_file_path = $wordpress_upload_dir['path'] . '/' . "PDF-product-" . time() . ".png";

			$imagick->cropImage($_POST['cropW'], $_POST['cropH'], $_POST['cropX'], $_POST['cropY']);
			$imagick->writeImage($new_file_path);

			// add image to WP gallery

			$upload_id = wp_insert_attachment(array(
				'guid'           => $new_file_path,
				'post_mime_type' => 'image/png',
				'post_title'     => 'Imagem do produto ' . $_POST['product-name'],
				'post_content'   => '',
				'post_status'    => 'inherit'
			), $new_file_path);

			$meta_data = wp_update_attachment_metadata($upload_id, wp_generate_attachment_metadata($upload_id, $new_file_path));

			// create product

			$product = new WC_Product();

			$product->set_name($_POST['product-name']);
			$product->set_sku($_POST['product-code']);
			$product->set_category_ids($_POST['category']);
			$product->set_description($_POST['notes']);
			$product->set_image_id($upload_id);
			$product->save();

			$id = $product->get_id();
			// update_post_meta($id, 'cropper-js', serialize(array(
			// 	$_POST['cropW'], $_POST['cropH'], $_POST['cropX'], $_POST['cropY']
			// )));
			update_post_meta($id, 'cropped', json_encode(array(
				'width' => $_POST['cropW'],
				'height' => $_POST['cropH'],
				'x' => $_POST['cropX'],
				'y' => $_POST['cropY'],
			), JSON_UNESCAPED_UNICODE));
			update_post_meta($id, 'variation',  $_POST['variation']);
			update_post_meta($id, '_height',  $_POST['_height']);
			update_post_meta($id, '_width',  $_POST['_width']);
			update_post_meta($id, '_length',  $_POST['_length']);
			update_post_meta($id, 'finishing',  $_POST['finishing']);
			update_post_meta($id, 'catalog_id',  $_POST['catalog_id']);
			update_post_meta($id, 'catalog_page',  $_POST['catalog_page']);

			echo json_encode(array(
				'status' => 'ok',
				'message' => 'Produto criado',
				'id' => $id
			), JSON_UNESCAPED_UNICODE);
			die();
		} catch (\Exception $error) {

			echo json_encode(array(
				'status' => 'error',
				'message' => $error,
				'error' => $error
			), JSON_UNESCAPED_UNICODE);
			die();
		}
	}


	public function save_catalogo(int $post_id, WP_Post $post)
	{
		// $post = get_post($post_id);
		$is_revision = wp_is_post_revision($post_id);

		if ($is_revision)
			return;

		$number_of_pages = get_post_meta($post_id, Smart_Catalog::META_KEY_NUMBER_OF_PAGES, true);

		$fields = array('fabricante');

		foreach ($fields as $field_name) {
			if (isset($_POST[$field_name])) {
				$field_value = trim($_POST[$field_name]);
				if (!empty($field_value)) {
					update_post_meta($post_id, $field_name, $field_value);
				} else {
					delete_post_meta($post_id, $field_name);
				}
			}
		}

		// Do not change status if post is published OR uploaded
		if ($post->post_status === 'publish' || $post->post_status === 'uploaded' || $post->post_status === 'trash')
			return;

		if ($number_of_pages != '' && intval($number_of_pages) > 0) {
			wp_update_post(array(
				'ID' => $post_id,
				'post_status' => 'uploaded'
			));
		}
	}
}
