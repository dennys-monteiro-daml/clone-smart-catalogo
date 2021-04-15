<?php
// $post = get_the_ID();
wp_nonce_field('add_pdf_nonce', 'add_pdf_nonce');
$id = get_the_ID();
$pages = get_post_meta($id, Smart_Catalog::META_KEY_NUMBER_OF_PAGES, true);
if ($pages !== '' && intval($pages) > 0) {


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
                <div class="button cancel" id="cancel-product">Cancelar</div>
                <div class="button" id="full-page">Página inteira</div>
            </td>
        </tr>
    </table>
    <div class="img-container">
        <img id="catalog-page" src="<?php echo Pdf_To_Woocommerce_Admin::get_upload_url($id)
                                        . Pdf_To_Woocommerce_Admin::PDF_CONVERTED_FOLDER
                                        . DIRECTORY_SEPARATOR
                                        . "0.png" ?>" class="img-fluid" />
    </div>
    <div id="new-product-form">
        <div class="img-preview"></div>

        <table class="form-table">
            <tr>
                <th> <label for="category">Categoria</label></th>
                <td>
                    <input name="cropper-js" id="cropper-js" value="" type="hidden" />
                    <?php
                    echo get_woocommerce_categories_selector('category');
                    ?>
                </td>
            </tr>
            <tr>
                <th> <label for="product-name">Nome do produto</label></th>
                <td>
                    <input name="product-name" id="product-name" value="" />
                </td>
            </tr>
            <tr>
                <th> <label for="variation">Variação</label></th>
                <td>
                    <input name="variation" id="variation" value="" />
                </td>
            </tr>
            <tr>
                <th> <label for="dimensions">Dimensões</label></th>
                <td>
                    <input name="dimensions" id="dimensions" value="" />
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
                    <div class="button button-primary" id="save-product">Salvar</div>
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
