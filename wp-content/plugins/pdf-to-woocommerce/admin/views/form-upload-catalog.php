<?php
// $post = get_the_ID();
wp_nonce_field('add_pdf_nonce', 'add_pdf_nonce');
$id = get_the_ID();
$pages = get_post_meta($id, Smart_Catalog::META_KEY_NUMBER_OF_PAGES, true);
if ($pages !== '' && intval($pages) > 0) {

    $posts = new Post_Data();

    $posts = $posts->find("post_type = 'product' AND post_status = 'publish'")->fetch(true);

    if (!is_null($posts)) {
        Post_Meta_Data::attach_meta($posts);

        // print_r($posts[0]->post_meta);

        $arr = array_values(array_filter($posts, function ($post) use ($id) {
            return isset($post->post_meta['catalog_id']) && $post->post_meta['catalog_id'] == $id;
        }));

        $arr = array_map(
            function ($item) {
                return array(
                    'data' => $item->data(),
                    'post_meta' => $item->post_meta,
                    // 'item' => $item
                );
            },
            $arr
        );

    }

    if (!isset($arr)) {
        $arr = array();
    }

    wp_enqueue_script("pdf-products", PDF_TO_WOOCOMMERCE_URL . "admin/js/products.js");
    wp_localize_script("pdf-products", "wp_products", array(
        'products' => $arr,
        'admin_url' => get_admin_url(),
    ));


?>
    <table class="form-table">
        <tr>
            <th> <select name="page-selector" id="page-selector">
                    <?php for ($i = 0; $i < $pages; $i++) { ?>
                        <option value="<?php echo $i ?>">Página <?php echo ($i + 1) ?></option>
                    <?php } ?>
                </select></th>
            <td>
                <div class="button button-primary" id="create-product">Criar Produto</div>
                <div class="button" id="add-to-product">Adicionar a um produto existente</div>
                <div class="button cancel" id="delete-pdf">Excluir PDF</div>
                <div class="button cancel" id="cancel-product" style="display: none">Cancelar</div>
                <div class="button" id="full-page" style="display: none">Página inteira</div>
                <!-- <i class="fas fa-edit"></i> -->
            </td>
        </tr>
    </table>
    <div class="product-overlay" id="main-product-overlay" hidden>

    </div>
    <div class="img-container">
        <img id="catalog-page" src="<?php echo Pdf_To_Woocommerce_Admin::get_upload_url($id)
                                        . Pdf_To_Woocommerce_Admin::PDF_CONVERTED_FOLDER
                                        . DIRECTORY_SEPARATOR
                                        . "0.png" ?>" class="img-fluid" />
    </div>
    <div id="new-product-form" style="display: none">
        <div class="img-preview" style="width: 300px; height: 300px;"></div>

        <table class="form-table">
            <tr id='row-category-0'>
                <th> <label for="category-0">Categoria</label></th>
                <td>
                    <input name="cropper-js" id="cropper-js" value="" type="hidden" />
                    <select name="category-0" id="category-0">
                        <?php
                        echo get_woocommerce_categories_options();
                        ?>
                    </select>
                    <div class='button button-primary sm-button' id='add-category'>+</div>
                    <div class='button cancel sm-button' id='rm-category' style="display: none">-</div>
                </td>
            </tr>
            <tr>
                <th> <label for="product-name">Nome do produto</label></th>
                <td>
                    <input name="product-name" id="product-name" value="" />
                </td>
            </tr>
            <tr>
                <th> <label for="product-code">Código</label></th>
                <td>
                    <input name="product-code" id="product-code" value="" />
                </td>
            </tr>
            <tr>
                <th> <label for="variation">Variação</label></th>
                <td>
                    <input name="variation" id="variation" value="" />
                </td>
            </tr>
            <tr>
                <th> <label>Dimensões (cm)</label></th>
                <td>
                    <input name="_length" id="_length" placeholder="Comprimento" class="input-sm" value="" />
                    <input name="_width" id="_width" placeholder="Largura" class="input-sm" value="" />
                    <input name="_height" id="_height" placeholder="Altura" class="input-sm" value="" />
                </td>
            </tr>
            <tr>
                <th> <label for="finishing">Acabamento</label></th>
                <td>
                    <input name="finishing" id="finishing" value="" />
                </td>
            </tr>
            <tr>
                <th> <label for="notes">Observações</label></th>
                <td>
                    <textarea name="notes" id="notes" value=""></textarea>
                </td>
            </tr>


            <tr>
                <th></th>
                <td>
                    <div style="max-width: 100px;">
                        <div class="button button-primary" id="save-product">Salvar</div>
                        <span id="save-product-spinner" class="spinner"></span>
                    </div>
                    <!-- <a href="#upload-progress" rel="modal:open">Open Modal</a> -->
                </td>
            </tr>

        </table>
    </div>

<?php


} else {
?>
    <table class="form-table">

        <tr>
            <th> <label for="file">PDF</label></th>
            <td>
                <input type="hidden" id="post_ID" value="<?php echo get_the_ID() ?>" />
                <input name="file" id="file" type="file" value="" />
            </td>
        </tr>
        <tr>
            <th></th>
            <td>
                <div class="button" id="upload-submit">Fazer o Upload</div>
                <!-- <a href="#upload-progress" rel="modal:open">Open Modal</a> -->
            </td>
        </tr>

    </table>
    <div id="upload-progress" class="modal">
        <h1 id="upload-message"></h1>
        <progress id="upload-bar" value="0" max="100"></progress>

    </div>
<?php
}
