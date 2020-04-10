'use strict';

require("jquery-ui/ui/widgets/sortable");

window.printImage = function(src, idn){
    if (idn === 'product') {
        let list = $('#image_list');
        let n = list.data('widget-counter') | list.children().length;
        let newWidget = list.attr('data-prototype');
        newWidget = newWidget.replace(/__name__/g, n);
        newWidget = newWidget.replace(/__src__/g, src);
        n++;
        list.data('widget-counter', n);
        let newElem = $(list.attr('data-widget-tags')).html(newWidget);
        newElem.appendTo(list);
    } else {
        let imgObj = $('#' + idn);
        let block = imgObj.closest('.img-block');
        let inputObj = block.find('input.input-img-item');

        imgObj.attr('src', src);
        inputObj.val(src);
    }
};

function setPosition(obj){
    let x = 0;
    obj.find('.srt').each(function () {
        $(this).find('.input-position').val(x);
        x++;
    });
}

$(document).ready(function() {
    $(".sortable-list").sortable({
        items: ".srt",
        opacity: 0.5,
        containment: "body",
        handle: ".sort-handle",
        update: function () {
            setPosition($(this).closest('.sortable-list'));
        }
    });

    $(document).on('click', 'a.reset-img', function () {
        let block = $(this).closest('.img-block');
        let img = block.find('img.img-item');
        img.attr('src', img.attr('data-old-image'));
        block.find('input.input-img-item').val(img.attr('data-name-image'));
    });

    $(document).on('click', 'a.clear-img', function () {
        let block = $(this).closest('.img-block');

        let noImgAttr = block.find('img.img-item').attr('data-no-image');
        if (typeof noImgAttr !== typeof undefined && noImgAttr !== false) {
            block.find('img.img-item').attr('src', noImgAttr);
        }

        block.find('input.input-img-item').val('');
    });

    $(document).on('click', 'a.visible-img', function () {
        let obj = $(this);
        let input = obj.closest('.srt').find('input.img-visible');

        if (input.prop('checked')) {
            input.prop('checked', false).removeAttr('checked');
            obj.find('svg').removeClass('fa-eye text-dark').addClass('fa-eye-slash text-danger');
        } else {
            input.prop('checked', true).attr('checked', 'checked');
            obj.find('svg').removeClass('fa-eye-slash text-danger').addClass('fa-eye text-dark');
        }
    });

    $(document).on('click', 'a.delete-element', function () {
        let obj = $(this);
        let box = obj.closest('.sortable-list');
        obj.closest('.srt').remove();
        setPosition(box);
    });

    $('.add-another-collection-widget').click(function (e) {
        e.preventDefault();
        let list = $($(this).attr('data-list'));
        let counter = list.data('widget-counter') | list.children().length;
        if (!counter) { counter = list.children().length; }
        let newWidget = list.attr('data-prototype');
        newWidget = newWidget.replace(/__name__/g, counter);
        counter++;
        list.data('widget-counter', counter);
        let newElem = $(list.attr('data-widget-tags')).html(newWidget);
        newElem.appendTo(list);
    });

    $(document).on('change', '.calc-price', function () {
        let container = $(this).closest('.collection-widget-item');
        let price = container.find('.product_price').val();
        if (price === '') {
            price = 0;
        } else {
            let coefficient = container.find('.product_coefficient').val();
            if (coefficient !== '') {
                let c = container.find('.product_coefficient').find('option[value=' + coefficient + ']').attr('data-coefficient');
                if (c !== 'undefined' && c !== '') {
                    price = price * c;
                }
            }
        }

        container.find('.price-no-discount').text(Math.round(price));

        let discount_percentage = container.find('.product_discount_percentage').val();
        let price_d = 0;
        if (discount_percentage !== '') {
            price_d = (price * 1 - (price * discount_percentage / 100));
        }

        container.find('.price-yes-discount').text(Math.round(price_d));
    });

    $(document).on('keyup', '.calc-price', function () {
        $('.calc-price').change();
    });

    $('.calc-price').bind('paste', function() {
        $('.calc-price').change();
    });

    $('.calc-price').bind('cut', function() {
        $('.calc-price').change();
    });

    if ($('*').is('.calc-price')) {
        $('.calc-price').change();
    }
});
