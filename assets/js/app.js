import '../css/app.scss';

import $ from 'jquery';

global.jQuery = $;
global.$ = $;
global.lang = $('html').attr('lang');

import 'bootstrap';
import '@fortawesome/fontawesome-free/js/all.js'

$(document).ready(function() {
    $('[data-toggle="popover"]').popover();
});
