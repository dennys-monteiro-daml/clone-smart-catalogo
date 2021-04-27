(function ($) {

    $(document).ready(function() {
        const { products } = wp_products;
        console.log(products);

        $(window).on('resize', positionAnchors);

        function positionAnchors() {
            products.forEach(({ id, cropped, catalog_page }) => {

                const imageWidth = $(`#catalog-page-${catalog_page}`).width();
                const { naturalWidth } = document.querySelector(`#catalog-page-${catalog_page}`);
                const scale = imageWidth / naturalWidth;

                $(`#product-${id}`).css('margin-top', parseFloat(cropped.y) * scale);
                $(`#product-${id}`).css('margin-left', parseFloat(cropped.x) * scale);

            });
        }

        positionAnchors();
        setTimeout(positionAnchors, 1000);

    });


})(jQuery);