'use strict';

$(document).ready(function() {
    let nav = ['<i class="fal fa-chevron-left"></i>', '<i class="fal fa-chevron-right"></i>'];

    $('.owl-carousel-single').owlCarousel({
        loop:true,
        nav:true,
        navText: nav,
        animateOut: 'fadeOut',
        animateIn: 'flipInX',
        items: 1,
        smartSpeed: 450
    });

    $('.owl-carousel-single-no-nav').owlCarousel({
        loop:true,
        items: 1,
        autoplay: true,
        autoplayTimeout: 5000,
        autoplayHoverPause: true
    });

    $('.owl-carousel-middle').owlCarousel({
        loop:true,
        nav:true,
        navText: nav,
        margin:15,
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
            },
            768:{
                items:2,
            },
            992:{
                items:3,
            }
        }
    });

    $('.owl-carousel-big').owlCarousel({
        loop:true,
        nav:true,
        navText: nav,
        margin:15,
        responsiveClass:true,
        responsive:{
            0:{
                items:1,
            },
            576:{
                items:2,
            },
            768:{
                items:3,
            },
            992:{
                items:4,
            },
        }
    });
});