(function ($) {
    'use strict';

    $(document).ready(function () {

        const { products } = wp_products;
        let currentPage = 0;
        let numberOfItems = 0;

        console.log('products -> ', products);

        $('#page-selector').change(function () {
            currentPage = $(this).val();
            $('#catalog-page').off('load').on('load', function () {
                showCurrentPageProducts();
            });
        });


        function clearOverlays() {
            for (var i = 0; i < numberOfItems; i++) {
                $(`#overlay-${i}`).remove();
            }
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
                    <a href="/wp-admin/post.php?post=${item.data.ID}&action=edit" target="_blank"><i class="fas fa-edit"></i></a>
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