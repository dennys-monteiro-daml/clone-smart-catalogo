<?php
// $post = get_the_ID();
wp_nonce_field('add_pdf_nonce', 'add_pdf_nonce');
$id = get_the_ID();
$pages = get_post_meta($id, Smart_Catalog::META_KEY_NUMBER_OF_PAGES, true);
if ($pages !== '' && intval($pages) > 0) {
    for ($i = 0; $i < intval($pages); $i++) {
?>
        <img src="<?php echo Pdf_To_Woocommerce_Admin::get_upload_url($id)
                        . Pdf_To_Woocommerce_Admin::PDF_CONVERTED_FOLDER
                        . DIRECTORY_SEPARATOR
                        . "$i.png" ?>" />


    <?php
    }
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
                <button id="upload-submit">Fazer o Upload</button>
            </td>
        </tr>

    </table>

<?php
}
