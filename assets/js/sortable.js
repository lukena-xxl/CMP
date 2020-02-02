'use strict';

require("jquery-ui/ui/widgets/sortable");

$(document).ready(function() {
    $('.content').find('.sortable').each(function(){
        let obj = $(this);
        obj.sortable({
            items: '.sort',
            opacity: 0.5,
            containment: 'body',
            handle: '.sort-handle',
            update: function(){
                let order = obj.sortable('toArray');
                let items = order.join(',');

                if ($('*').is('#sortable_sorted_data')) {
                    $('#sortable_sorted_data').val(items);
                }
            }
        });
    });
});
