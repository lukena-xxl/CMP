'use strict';

import Cropper from 'cropperjs';
let bytes = require('bytes');
let cropper = false;
let inputImage;

function hasAttribute (obj, attr) {
    let el = obj.attr(attr);
    return typeof el !== typeof undefined && el !== false;
}

function errorReporting(msg) {
    $.alert({
        icon: 'fas fa-exclamation-triangle',
        title: 'Внимание!',
        content: msg,
        type: 'red',
        typeAnimated: true
    });
}

function cropping()
{
    let ratio = NaN;
    if (hasAttribute(inputImage, "data-ratio")) {
        ratio = inputImage.attr('data-ratio');
    }

    const image = document.getElementById('img_crop');
    cropper = new Cropper(image, {
        aspectRatio: ratio,
        viewMode: 3,
        autoCropArea: 0.95,
        minContainerWidth: 300,
        minCropBoxWidth: 250,
        minCropBoxHeight: 140,
        crop(event) {
            console.log(event.detail.x);
            console.log(event.detail.y);
            console.log(event.detail.width);
            console.log(event.detail.height);
            console.log(event.detail.rotate);
            console.log(event.detail.scaleX);
            console.log(event.detail.scaleY);
        },
        ready(){},
    });

    let settingsCrop = '<div class="p-2 bg-dark w-100 text-center"><a href="javascript:void(0)" class="btn btn-light btn-sm mr-1 mr-sm-2 undo-crop" role="button"><i class="fas fa-undo"></i></a><a href="javascript:void(0)" class="btn btn-light btn-sm mr-1 mr-sm-2 redo-crop" role="button"><i class="fas fa-redo"></i></a><a href="javascript:void(0)" class="btn btn-sm btn-light mr-1 mr-sm-2 scale-crop" role="button"><i class="fas fa-exchange-alt"></i></a><a href="javascript:void(0)" class="btn btn-light btn-sm mr-1 mr-sm-2 move-crop" role="button"><i class="fas fa-crop-alt"></i></a><a href="javascript:void(0)" class="btn btn-light btn-sm mr-1 mr-sm-2 reset-crop" role="button"><i class="fas fa-sync"></i></a><a href="javascript:void(0)" class="btn btn-light btn-sm disable-crop" role="button"><i class="fas fa-lock-open text-success"></i></a></div><a href="javascript:void(0);" class="btn btn-primary mt-2" id="load-image">Добавить</a>';

    $('#selected_image').append(settingsCrop);

    $('a.undo-crop').click(function(){
        cropper.rotate(-45);
    });

    $('a.redo-crop').click(function(){
        cropper.rotate(45);
    });

    let scaleCrop = -1;
    $('a.scale-crop').click(function(){
        cropper.scaleX(scaleCrop);
        if (scaleCrop < 0) {
            scaleCrop = 1;
        } else {
            scaleCrop = -1;
        }
    });

    let dragCrop = 'move';
    $('a.move-crop').click(function(){
        let obj = $(this).find('svg');
        cropper.setDragMode(dragCrop);
        if (dragCrop === 'move') {
            dragCrop = 'crop';
            obj.removeClass('fa-crop-alt').addClass('fa-arrows-alt');
        } else {
            dragCrop = 'move';
            obj.removeClass('fa-arrows-alt').addClass('fa-crop-alt');
        }
    });

    $('a.disable-crop').click(function(){
        let obj = $(this).find('svg');
        if (obj.hasClass('fa-lock-open')) {
            cropper.disable();
            obj.removeClass('fa-lock-open text-success').addClass('fa-lock text-danger');
        } else {
            cropper.enable();
            obj.removeClass('fa-lock text-danger').addClass('fa-lock-open text-success');
        }
    });

    $('a.reset-crop').click(function(){
        cropper.reset();
    });
}

$(document).ready(function() {
    $(document).on('click', 'a.add-image', function(e){
        let obj = $(this);
        inputImage = obj;
        let title;

        if (hasAttribute(obj,"data-title")) {
            title = obj.attr('data-title');
        }

        let modal = $('#modal');
        modal.find('.modal-title').text(title);
        modal.find('.modal-body').html('<i class="fas fa-spinner fa-spin mr-2"></i>loading ...').ready(function(){
            modal.modal({
                backdrop: 'static'
            });
            modal.find('.modal-body').load('/admin/common/cropping/image');
        });
    });

    $(document).on('change', 'input#chose-img', function () {
        let obj = $(this);

        let maxSize = false;
        if (hasAttribute(inputImage,"data-max-size")) {
            maxSize = inputImage.attr('data-max-size');
        }

        let error = false;

        console.log(this.files);
        let fileObj = this.files[0];
        let type_file = fileObj.type;
        let mime = ['image/jpeg'];
        if (mime.indexOf(type_file) !== -1) {
            let size_file = fileObj.size;
            if (maxSize && maxSize <= size_file) {
                error = 'Размер файла должен быть не более ' + bytes(Number(maxSize)) + '. Ваш файл: ' + bytes(size_file);
                errorReporting(error);
            }

            if (!error) {
                let info = '<div class="text-white small p-2 bg-dark"><span>Тип: <strong>' + type_file + '</strong>; </span><span>Размер: <strong>' + bytes(size_file) + '</strong></span></div>';
                let reader = new FileReader();
                reader.onload = function (event) {
                    let src = event.target.result;
                    let image = new Image();
                    image.src = src;
                    image.onload = function () {
                        let w = false;
                        let h = false;
                        if (hasAttribute(inputImage,"data-min-length")) {
                            let wh = inputImage.attr('data-min-length');
                            if (wh !== '') {
                                let arr = wh.split('/');
                                if (typeof arr[0] !== typeof undefined && arr[0] !== '') {
                                    w = arr[0];
                                }

                                if (typeof arr[1] !== typeof undefined && arr[1] !== '') {
                                    h = arr[1];
                                }
                            }
                        }

                        if ((w && w > image.naturalWidth) || (h && h > image.naturalHeight)) {
                            error = 'Изображение должно быть не менее ' + w + 'px по ширине и ' + h + 'px по высоте. Ваше изображение: ' + image.naturalWidth + 'x' + image.naturalHeight + 'px';
                            errorReporting(error);
                        }

                        if (!error) {
                            obj.next('label').text(fileObj.name);
                            $('#selected_image').html(info + '<img src="' + src + '" class="img-fluid mt-2" id="img_crop" />');
                            cropping();
                        }
                    }
                };

                reader.readAsDataURL(fileObj);
            }
        } else {
            error = 'Разрешено загружать файлы: ' + mime.join(', ') + '. Ваш файл: ' + type_file;
            errorReporting(error);
        }
    });

    $(document).on('click', 'a#load-image', function () {
        let w = false;
        let h = false;
        if (hasAttribute(inputImage,"data-output-length")) {
            let wh = inputImage.attr('data-output-length');
            if (wh !== '') {
                let arr = wh.split('/');
                if (typeof arr[0] !== typeof undefined && arr[0] !== '') {
                    w = arr[0];
                }

                if (typeof arr[1] !== typeof undefined && arr[1] !== '') {
                    h = arr[1];
                }
            }
        }

        let dataURL = cropper.getCroppedCanvas({
            width: w,
            height: h,
            fillColor: '#fff',
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        }).toDataURL('image/jpeg', 0.9);

        $('#modal').modal('hide');

        let idn = null;
        if (hasAttribute(inputImage,"data-idn")) {
            idn = inputImage.attr('data-idn');
        }

        window.printImage(dataURL, idn);
    });
});

/* In Twig
<a href="#"
class="add-image"
data-ratio="{{ 800/600 }}" ..................... соотношение сторон, равно 1,33333
data-min-length="800/600" ...................... минимальная ширина и высота входящего изображения в пикселях, равно 800px на 600px (можно укзать только ширину "800/" или высоту "/600")
data-max-size="{{ 3*1000*1000 }}" .............. максимальный размер входящего изображения в байтах
data-output-length = "800/600" ................. ширина и высота выходящего изображения  (можно укзать только ширину "800/" или высоту "/600")
data-idn = "" .................................. идентификатор для дальнейшей обработки изображения (не обязательный атрибут)
data-title=""></a>
*/
