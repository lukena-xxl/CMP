'use strict';

let loading = '<i class="fas fa-spinner fa-spin mr-2"></i>loading ...';

function hasAttribute(obj, attr) {
    let el = obj.attr(attr);
    return typeof el !== typeof undefined && el !== false;
}

function calc() {
    let cost = 0;

    $('#product_list').find('.srt').each(function () {
        let obj = $(this);
        let q = obj.find('.product-quantity').val();
        let p = obj.find('.product-price').val();

        cost += q*p;
    });

    $('#full_order_price').text(cost);
}

let nowSearchSite = '';

window.searchSite = function(txt) {
    nowSearchSite = txt;
    if(txt.length>1) {
        $('#result_search').html(loading);
        $.get('/admin/common/search/product/' + encodeURIComponent(txt), function(data) {
            if(data.txt == nowSearchSite) {
                $('#result_search').html(data.res);
            }
        });
    } else {
        $('#result_search').html('');
    }
};

$(document).ready(function() {
    calc();

    $(document).on('click', 'a.add-item', function () {
        let obj = $(this);
        let title;

        if (hasAttribute(obj, "data-title")) {
            title = obj.attr('data-title');
        }

        let modal = $('#modal');
        modal.find('.modal-title').text(title);
        modal.find('.modal-body').html(loading).ready(function() {
            modal.modal({
                backdrop: 'static'
            });
            modal.find('.modal-body').load('/admin/common/search/form');
        });
    });

    let timerSearchSite = '';
    $(document).on('keyup', 'input#search_input', function(e) {
        e.preventDefault();
        let obj = $(this);
        if($.inArray(e.keyCode, [13, 16, 17, 18, 20, 27, 32, 37, 38, 39, 40]) == -1) {
            clearTimeout(timerSearchSite);
            timerSearchSite = window.setTimeout(function() {
                searchSite(obj.val());
            }, 700);
        }
    });

    $(document).on('paste', 'input#search_input', function() {
        setTimeout( function() {
            $('input#search_input').keyup();
        }, 30);
    });

    $(document).on('cut', 'input#search_input', function() {
        setTimeout( function() {
            $('input#search_input').keyup();
        }, 30);
    });

    $(document).on('click', 'a.delete-element', function () {
        let obj = $(this);
        obj.closest('.srt').remove();

        calc();
    });

    $(document).on('click', 'li.check-item', function () {
        let path = $(this).find('.path-form').html();
        let list = $('#product_list');
        let n = list.data('product-length') | list.children().length;
        path = path.replace(/__name__/g, n);
        n++;
        list.data('product-length', n);

        list.append(path);
        $('#modal').modal('hide');

        calc();
    });

    $(document).on('change', '.product-quantity', function () {
        calc();
    });
});
