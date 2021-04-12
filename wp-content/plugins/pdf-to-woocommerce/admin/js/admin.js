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
            $.ajax({
                type: 'POST',
                url: `${wp_object.admin_url}admin-ajax.php`,
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            }).success(function (data) {
                console.log('success!', data);
            })
        });
    });


})(jQuery);