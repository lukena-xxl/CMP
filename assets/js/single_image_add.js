'use strict';

window.printImage = function(src, idn){
    let imgObj = $('#' + idn);
    let block = imgObj.closest('.img-block');
    let inputObj = block.find('input.input-img');

    imgObj.attr('src', src);
    inputObj.val(src);
};

$(document).ready(function() {
    $(document).on('click', 'a.reset-img', function () {
        let block = $(this).closest('.img-block');
        let img = block.find('img.img-tag');
        img.attr('src', img.attr('data-old-image'));
        block.find('input.input-img').val(img.attr('data-name-image'));
    });

    $(document).on('click', 'a.clear-img', function () {
        let block = $(this).closest('.img-block');
        let noImgAttr = block.find('img.img-tag').attr('data-no-image');
        if (typeof noImgAttr !== typeof undefined && noImgAttr !== false) {
            block.find('img.img-tag').attr('src', noImgAttr);
        }

        block.find('input.input-img').val('');
    });
});
