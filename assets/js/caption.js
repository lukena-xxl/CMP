'use strict';

$(document).ready(function() {
    $('.caption_js').on('change', function () {
        let name = $('#product_caption_name').val();
        let color_text = $('#product_caption_color_text').val();
        let color_fill = $('#product_caption_color_fill').val();

        $('#result_caption').html('<div class="px-3 py-1 d-inline-block" style="color:' + color_text + '; background-color:' + color_fill + ';">' + name + '</div>');
    });

    $('.caption_js').change();
});
