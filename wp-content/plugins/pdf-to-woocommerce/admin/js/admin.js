(function ($) {
    'use strict';

    $(document).ready(function () {
        console.log('window ready!', wp_object);
        $('.img-preview').css('width', '300px');
        $('.img-preview').css('height', '300px');

        $('#cancel-product').hide();
        $('#full-page').hide();
        $('#new-product-form').hide();

        $('#upload-submit').on('click', function () {

            var formData = new FormData();
            formData.append('add_pdf_nonce', $('#add_pdf_nonce').val());
            const files = $('#file').prop('files');
            if (files.length > 0) {
                formData.append('file', files[0]);
            }
            formData.append('post_ID', $('#post_ID').val());
            formData.append('action', 'add_pdf');
            console.log(formData);
            // const data = {
            //     add_pdf_nonce: ,
            //     file: ,
            //     action: 'add_pdf',
            // };
            // console.log(data);
            $('#upload-progress').modal();
            $('#upload-message').text("Realizando upload - Não feche esta janela");
            // $('#upload-message').text("Conversão em andamento - Não feche esta janela");
            postRemote({
                type: 'POST',
                url: `${wp_object.admin_url}admin-ajax.php`,
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            }).then(async function (data) {
                if (typeof data === 'string') {
                    data = JSON.parse(data);
                }
                console.log('success!', data);
                if (data.status === 'ok') {
                    $('#upload-message').text("Conversão em andamento - Não feche esta janela");
                    for (var i = 0; i < data.pages; i++) {
                        var formConvert = new FormData();
                        formConvert.append('convert_pdf_nonce', wp_object.convert_pdf_nonce);
                        formConvert.append('post_ID', $('#post_ID').val());
                        formConvert.append('action', 'convert_pdf');
                        formConvert.append('page', i);
                        formConvert.append('pdf_location', data.pdf_location);

                        const response = await postRemote({
                            type: 'POST',
                            url: `${wp_object.admin_url}admin-ajax.php`,
                            data: formConvert,
                            cache: false,
                            contentType: false,
                            processData: false
                        });

                        console.log(`converted page ${i}`, response);

                        $('#upload-bar').attr('value', 100 * i / data.pages);

                    }
                }
            });
        });

        $('#page-selector').change(function () {
            console.log($(this).val());
            $('#catalog-page').attr('src', `${wp_object.plugin_admin_url}uploads/${$('#post_ID').val()}/${wp_object.converted_folder}/${$(this).val()}.png`);
        });

        $('#create-product').click(function () {
            $('#create-product').hide();
            $('#add-to-product').hide();
            $('#delete-pdf').hide();
            $('#page-selector').attr('disabled', 'true');
            $('#cancel-product').show();
            $('#full-page').show();
            $('#new-product-form').show();
            $('#catalog-page').cropper({
                preview: '.img-preview',
                zoomOnWheel: false,
                crop: function (event) {
                    $('#cropper-js').val(JSON.stringify(event.detail));

                }
            });
        });

        $('#cancel-product').click(function () {
            $('#create-product').show();
            $('#add-to-product').show();
            $('#delete-pdf').show();
            $('#cancel-product').hide();
            $('#full-page').hide();
            $('#new-product-form').hide();
            $('#page-selector').removeAttr('disabled');
            $('#catalog-page').cropper('destroy');

        });

        $('#full-page').click(function () {

            const { naturalWidth, naturalHeight } = $('#catalog-page').cropper('getImageData');
            $('#catalog-page').cropper('setData', {
                x: 0,
                y: 0,
                width: naturalWidth,
                height: naturalHeight
            });

        });

        $('#save-product').click(function () {

            var formData = new FormData();
            formData.append('action', 'create_product');
            // formData.append('cropper-js', $('#cropper-js').val());

            const cropperData = JSON.parse($('#cropper-js').val());

            formData.append('cropX', cropperData.x);
            formData.append('cropY', cropperData.y);
            formData.append('cropW', cropperData.width);
            formData.append('cropH', cropperData.height);

            formData.append('product-name', $('#product-name').val());
            formData.append('product-code', $('#product-code').val());
            formData.append('category[0]', $('#category-0').val());
            formData.append('variation', $('#variation').val());
            formData.append('_height', $('#_height').val());
            formData.append('_length', $('#_length').val());
            formData.append('_width', $('#_width').val());
            formData.append('finishing', $('#finishing').val());
            formData.append('notes', $('#notes').val());
            formData.append('catalog_id', $('#post_ID').val());
            formData.append('catalog_page', $('#page-selector').val());
            postRemote({
                type: 'POST',
                url: `${wp_object.admin_url}admin-ajax.php`,
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            }).then(result => {
                console.log('got result!', result);
            })

        });

        $('#add-category').click(function () {

        });

    });


    function postRemote(options) {
        return new Promise((resolve, reject) => {
            try {
                $.ajax(options).success(resolve);
            } catch (error) {
                reject(error);
            }
        });
    }

})(jQuery);