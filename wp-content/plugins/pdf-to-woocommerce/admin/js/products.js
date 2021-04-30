(function ($) {
    'use strict';

    $(document).ready(function () {

        const { products, admin_url, product_cat } = wp_products;
        let currentPage = 0;
        let numberOfItems = 0;
        let numberOfCategories = 1;

        console.log('products -> ', products);

        $('#page-selector').change(function () {
            currentPage = $(this).val();
            $('#catalog-page').off('load').on('load', function () {
                showCurrentPageProducts();
            });
        });

        $('#save-product').click(function () {

            $(this).attr('disabled', 'true');
            $(this).addClass('disabled');

            $('#save-product-spinner').addClass('is-active');

            var formData = new FormData();
            formData.append('action', 'create_product');

            const cropperData = JSON.parse($('#cropper-js').val());

            formData.append('cropX', cropperData.x);
            formData.append('cropY', cropperData.y);
            formData.append('cropW', cropperData.width);
            formData.append('cropH', cropperData.height);

            formData.append('product-name', $('#product-name').val());
            formData.append('product-code', $('#product-code').val());

            for (var i = 0; i < numberOfCategories; i++) {
                formData.append(`category[${i}]`, $(`#category-${i}`).val());
            }

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
                url: `${admin_url}admin-ajax.php`,
                data: formData,
                cache: false,
                contentType: false,
                processData: false
            }).then((result) => {

                $('#save-product-spinner').removeClass('is-active');

                if (typeof result === 'string') {
                    result = JSON.parse(result);
                }

                console.log('got result!', result);

                if (result.status === 'ok') {
                    toastr.success(`Produto criado com sucesso! ID do produto: ${result.id}.`);

                    products.push({
                        data: {
                            ID: result.id,
                            post_title: $('#product-name').val()
                        },
                        post_meta: {
                            catalog_page: $('#page-selector').val(),
                            cropped: JSON.stringify(cropperData)
                        }
                    });

                    $('#create-product').show();
                    $('#add-to-product').show();
                    $('#delete-pdf').show();
                    $('#cancel-product').hide();
                    $('#full-page').hide();
                    $('#new-product-form').hide();
                    $('#page-selector').removeAttr('disabled');
                    $('#catalog-page').cropper('destroy');

                    clearCategories();

                    $('#product-name').val('');
                    $('#product-code').val('');
                    $('#category-0').val('');
                    $('#variation').val('');
                    $('#_height').val('');
                    $('#_length').val('');
                    $('#_width').val('');
                    $('#finishing').val('');
                    $('#notes').val('');

                    showCurrentPageProducts();

                    return;
                }
                toastr.error(`Houve um erro na criação do produto: ${result.message}`);


            })

        });

        $(window).on('resize', function () {
            showCurrentPageProducts();
        });

        $('#add-category').click(function () {
            const html = `<p class="category-wrapper-${numberOfCategories}"><select name="category-${numberOfCategories}" id="category-${numberOfCategories}">
                    ${product_cat}
                </select></p>`;
            console.log(html);
            $(html).insertAfter(`.category-wrapper-${numberOfCategories - 1}`);
            numberOfCategories++;
        });

        $('#remove-category').click(removeCategory);

        function clearOverlays() {
            for (var i = 0; i < numberOfItems; i++) {
                $(`#overlay-${i}`).remove();
            }
        }

        function clearCategories() {
            while (numberOfCategories > 1) {
                removeCategory();
            }
        }

        function removeCategory() {
            if (numberOfCategories == 1) return;
            $(`.category-wrapper-${numberOfCategories - 1}`).remove();
            numberOfCategories--;
        }

        function showCurrentPageProducts() {
            const items = products.filter((item) => item.post_meta.catalog_page == currentPage);
            console.log(items);
            clearOverlays();

            // if (items.length === 0) {

            //     return;
            // }

            items.forEach((item, i) => {

                $(`<div class="product-overlay" id="overlay-${i}">
                    <a href="/wp-admin/post.php?post=${item.data.ID}&action=edit" target="_blank">${item.data.post_title} <i class="fas fa-edit"></i></a>
                </div>`).insertAfter('#main-product-overlay');

                console.log('will render item ', item);

                const imageWidth = $('#catalog-page').width(); // .width(): number;
                // const imageHeight = $('#catalog-page').css('height'); // .height(): number;

                const { naturalWidth } = document.querySelector('#catalog-page');

                const scale = imageWidth / naturalWidth;

                console.log('scale -> ', scale);

                const cropper = JSON.parse(item.post_meta.cropped);
                console.log(cropper);

                $(`#overlay-${i}`).css('width', cropper.width * scale);
                $(`#overlay-${i}`).css('height', cropper.height * scale);
                $(`#overlay-${i}`).css('margin-top', cropper.y * scale);
                $(`#overlay-${i}`).css('margin-left', cropper.x * scale);

            });

            window.updateIcons();

            numberOfItems = items.length;
        }

        showCurrentPageProducts();

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