'use strict';

function calculate()
{
    let full_price = 0;
    let full_price_old = 0;
    let only_price_old = 0;

    $('.product-items-block').find('.img-check').each(function () {
        let obj = $(this);
        let checkbox = obj.find('input[type="checkbox"]');

        if (checkbox.prop('checked') === true) {
            let price = checkbox.attr('data-price');
            let price_old = checkbox.attr('data-price-old');
            let count = checkbox.closest('.row').find('input.count-product').val();

            if ((price != 0 && price !== '') && (count != 0 && count !== '')) {
                full_price += price*count;

                if (price_old != 0 && price_old !== '') {
                    full_price_old += price_old*count;
                    only_price_old = full_price_old;
                } else {
                    full_price_old += price*count;
                }
            }
        }
    });

    $('#main-price').text(full_price);

    if (only_price_old == 0) {
        $('#main-price-old').closest('.price-old').addClass('d-none');
        $('#saving').text('0').closest('.saving-block').addClass('d-none');
    } else {
        $('#main-price-old').text(full_price_old).closest('.price-old').removeClass('d-none');

        let saving = full_price_old - full_price;
        $('#saving').text(saving).closest('.saving-block').removeClass('d-none');
    }

    if (full_price == 0) {
        $('.add-to-card').addClass('disabled').prop('disabled', true);
    } else {
        $('.add-to-card').removeClass('disabled').prop('disabled', false);
    }
}

$(document).ready(function() {

    $('.checked-img').on('click', function () {
        let img = $(this);
        let checkbox = img.closest('.img-check').find('input[type="checkbox"]');

        if (!checkbox.prop('checked')) {
            checkbox.prop('checked', true);
            img.addClass('checked');
        } else {
            checkbox.prop('checked', false);
            img.removeClass('checked');
        }

        calculate();
    });

    $('button.count-prev').on('click', function () {
        let input = $(this).closest('.count-block').find('input.count-product');
        let val = input.val();

        if (input.attr('min') !== undefined) {
            let min = input.attr('min');
            if (val > min) {
                val--;
            }
        } else {
            val--;
        }

        input.val(val);

        calculate();
    });

    $('button.count-next').on('click', function () {
        let input = $(this).closest('.count-block').find('input.count-product');
        let val = input.val();

        if (input.attr('max') !== undefined) {
            let max = input.attr('max');
            if (val < max) {
                val++;
            }
        } else {
            val++;
        }

        input.val(val);

        calculate();
    });
});
