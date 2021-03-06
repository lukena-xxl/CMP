'use strict';

import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import '@ckeditor/ckeditor5-build-classic/build/translations/ru.js';
import '@ckeditor/ckeditor5-build-classic/build/translations/uk.js';

$('body').append('<script type="text/javascript" src="/plugins/ckfinder/ckfinder.js"></script>');

let allEditors = document.querySelectorAll('.editor');
for (let i = 0; i < allEditors.length; ++i) {
    ClassicEditor
        .create(allEditors[i], {
            ckfinder: {
                uploadUrl: '/plugins/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
            },
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'insertTable', '|', 'mediaEmbed', 'ckfinder', 'imageUpload'],
            language: lang
        })
        .then(editor => {
            console.log(editor);
        })
        .catch(error => {
            console.error(error);
        });
}