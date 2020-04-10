import '../css/app.scss';

import $ from 'jquery';

global.jQuery = $;
global.$ = $;
global.lang = $('html').attr('lang');

import 'bootstrap';
import '@fortawesome/fontawesome-pro/js/all.js'
import 'jquery-confirm';

let owl_carousel = require('owl.carousel');
window.fn = owl_carousel;

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
    $('[data-toggle="tooltip"]').tooltip();

    $(document).on('hidden.bs.modal', '#modal', function(event) {
        var obj = $(this);
        obj.find('.modal-title').text('');
        obj.find('.modal-body').html('');
        obj.removeData();
    });

});
