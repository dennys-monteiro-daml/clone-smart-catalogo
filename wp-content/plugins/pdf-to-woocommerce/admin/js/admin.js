(function ($) {
    'use strict';

    $(document).ready(function () {
        console.log('window ready!', wp_object);
        // $('.img-preview').css('width', '300px');
        // $('.img-preview').css('height', '300px');

        // $('#cancel-product').hide();
        // $('#full-page').hide();
        // $('#new-product-form').hide();

        $('#upload-submit').on('click', async function () {

            try {

                var formData = new FormData();
                formData.append('add_pdf_nonce', $('#add_pdf_nonce').val());
                const files = $('#file').prop('files');
                if (files.length > 0) {
                    formData.append('file', files[0]);
                }
                formData.append('post_ID', $('#post_ID').val());
                formData.append('action', 'add_pdf');
                console.log(formData);

                $('#upload-progress').modal({
                    escapeClose: false,
                    clickClose: false,
                    showClose: false
                });

                $('#upload-message').text("Realizando upload - Não feche esta janela");

                const data = await postRemote({
                    type: 'POST',
                    url: `${wp_object.admin_url}admin-ajax.php`,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false
                });

                if (typeof data === 'string') {
                    data = JSON.parse(data);
                }
                console.log('success!', data);

                if (data.status !== 'ok') {
                    toastr.error(`Houve um erro no envio: ${data.message}`);
                    return;
                }

                // if (data.status === 'ok') {

                for (var i = 0; i < data.pages; i++) {
                    $('#upload-message').html(`Conversão em andamento - Não feche esta janela<br/>Convertendo página ${i + 1} de ${data.pages}`);
                    var formConvert = new FormData();
                    formConvert.append('convert_pdf_nonce', wp_object.convert_pdf_nonce);
                    formConvert.append('post_ID', $('#post_ID').val());
                    formConvert.append('action', 'convert_pdf');
                    formConvert.append('page', i);
                    formConvert.append('pdf_location', data.pdf_location);

                    const { status, message } = JSON.parse(await postRemote({
                        type: 'POST',
                        url: `${wp_object.admin_url}admin-ajax.php`,
                        data: formConvert,
                        cache: false,
                        contentType: false,
                        processData: false
                    }));

                    if (status !== "ok") {
                        toastr.error(`Houve um erro na conversão: ${message}`);
                    }

                    $('#upload-bar').attr('value', 100 * i / data.pages);

                    if (i + 1 === data.pages) {
                        $.modal.close();
                        toastr.success('Pdf convertido com sucesso!');
                        setTimeout(() => {
                            $('#save-post').click();
                        }, 200);
                    }

                }
                //}


            } catch (error) {
                toastr.error(`Houve um erro no processamento: ${error}`);
            }


        });

        $('#page-selector').change(function () {
            console.log($(this).val());
            $('#catalog-page')
                .attr(
                    'src',
                    `${wp_object.plugin_admin_url}uploads/${$('#post_ID').val()}/${wp_object.converted_folder}/${$(this).val()}.png`
                );
        });

        $('#create-product').click(function () {

            $('#create-product').hide();
            $('#add-to-product').hide();
            $('#delete-pdf').hide();
            $('#page-selector').attr('disabled', 'true');
            $('#cancel-product').show();
            $('#full-page').show();
            $('#new-product-form').show();
            $('#save-product').removeAttr('disabled');
            $('#save-product').removeClass('disabled')
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

        // $('#save-product').click(function () {

        //     $(this).attr('disabled', 'true');
        //     $(this).addClass('disabled');

        //     $('#save-product-spinner').addClass('is-active');

        //     var formData = new FormData();
        //     formData.append('action', 'create_product');

        //     const cropperData = JSON.parse($('#cropper-js').val());

        //     formData.append('cropX', cropperData.x);
        //     formData.append('cropY', cropperData.y);
        //     formData.append('cropW', cropperData.width);
        //     formData.append('cropH', cropperData.height);

        //     formData.append('product-name', $('#product-name').val());
        //     formData.append('product-code', $('#product-code').val());
        //     formData.append('category[0]', $('#category-0').val());
        //     formData.append('variation', $('#variation').val());
        //     formData.append('_height', $('#_height').val());
        //     formData.append('_length', $('#_length').val());
        //     formData.append('_width', $('#_width').val());
        //     formData.append('finishing', $('#finishing').val());
        //     formData.append('notes', $('#notes').val());
        //     formData.append('catalog_id', $('#post_ID').val());
        //     formData.append('catalog_page', $('#page-selector').val());

        //     postRemote({
        //         type: 'POST',
        //         url: `${wp_object.admin_url}admin-ajax.php`,
        //         data: formData,
        //         cache: false,
        //         contentType: false,
        //         processData: false
        //     }).then((result) => {

        //         $('#save-product-spinner').removeClass('is-active');

        //         if (typeof result === 'string') {
        //             result = JSON.parse(result);
        //         }

        //         console.log('got result!', result);

        //         if (result.status === 'ok') {
        //             toastr.success(`Produto criado com sucesso! ID do produto: ${result.id}.`);

        //             $('#create-product').show();
        //             $('#add-to-product').show();
        //             $('#delete-pdf').show();
        //             $('#cancel-product').hide();
        //             $('#full-page').hide();
        //             $('#new-product-form').hide();
        //             $('#page-selector').removeAttr('disabled');
        //             $('#catalog-page').cropper('destroy');

        //             $('#product-name').val('');
        //             $('#product-code').val('');
        //             $('#category-0').val('');
        //             $('#variation').val('');
        //             $('#_height').val('');
        //             $('#_length').val('');
        //             $('#_width').val('');
        //             $('#finishing').val('');
        //             $('#notes').val('');

        //             return;
        //         }
        //         toastr.error(`Houve um erro na criação do produto: ${result.message}`);


        //     })

        // });

        $('#add-category').click(function () {
            // TODO add + de 1 categoria
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