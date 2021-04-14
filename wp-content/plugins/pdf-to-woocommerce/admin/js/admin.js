(function ($) {
    'use strict';

    $(document).ready(function () {
        console.log('window ready!', wp_object);
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
            })
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