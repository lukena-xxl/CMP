import '../css/app.scss';

import $ from 'jquery';

global.jQuery = $;
global.$ = $;
global.lang = $('html').attr('lang');

import 'bootstrap';
import '@fortawesome/fontawesome-pro/js/all.js'

let owl_carousel = require('owl.carousel');
window.fn = owl_carousel;

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
    $('[data-toggle="tooltip"]').tooltip()

});
