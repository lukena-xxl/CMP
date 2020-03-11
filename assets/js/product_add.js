'use strict';

$(document).ready(function() {
    $('.calc-price').on('change', function () {
        let price = $('#product_price').val();

        if (price === '') {
            price = 0;
        } else {
            let coefficient = $('#product_coefficient').val();
            if (coefficient !== '') {
                let c = $('#product_coefficient').find('option[value=' + coefficient + ']').attr('data-coefficient');
                if (c !== 'undefined' && c !== '') {
                    price = price * c;
                }
            }
        }

        $('#price-no-discount').text(Math.round(price));

        let discount_percentage = $('#product_discount_percentage').val();
        let price_d = 0;
        if (discount_percentage !== '') {
            price_d = (price * 1 - (price * discount_percentage / 100));
        }

        $('#price-yes-discount').text(Math.round(price_d));
    });

    $('.calc-price').on('keyup', function () {
        $('.calc-price').change();
    });

    $('.calc-price').bind('paste', function() {
        $('.calc-price').change();
    });

    $('.calc-price').bind('cut', function() {
        $('.calc-price').change();
    });

    $('.calc-price').change();
});
