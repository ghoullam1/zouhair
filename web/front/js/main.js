(function ($) {
    "use strict";
    $(document).ready(function ($) {
        /*----- Mobile Menu -----*/
        $('.mobile-menu nav').meanmenu({
            meanScreenWidth: "990",
            meanMenuContainer: ".mobile-menu",
        });

        /*----- main slider -----*/
        $('#mainSlider').nivoSlider({
            directionNav: false,
            animSpeed: 1000,
            slices: 18,
            pauseTime: 6500,
            controlNav: true,
            prevText: '<i class="fa fa-angle-left nivo-prev-icon"></i>',
            nextText: '<i class="fa fa-angle-right nivo-next-icon"></i>'
        });

        /*Owl Carousel for Weekly Featured Products*/
        $(".feature-pro-slider, .related-pro-slider").owlCarousel({
            nav: true,
            margin: 30,
            dots: false,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            responsive: {
                0: {items: 1, },
                480: {items: 2, },
                750: {items: 3, },
                950: {items: 4, },
                1170: {items: 4, },
            }
        });
        /*Owl Carousel for Weekly Featured Products*/
        $(".tab-pro-slider").owlCarousel({
            nav: true,
            margin: 30,
            dots: false,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            responsive: {
                0: {items: 1, },
                480: {items: 2, },
                750: {items: 3, },
                950: {items: 4, },
                1170: {items: 4, },
            }
        });
        /*Owl Carousel for Weekly Featured Products*/
        $(".tab-pro-slider-2").owlCarousel({
            loop: true,
            nav: true,
            margin: 30,
            dots: false,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            responsive: {
                0: {items: 1, },
                480: {items: 2, },
                750: {items: 3, },
            }
        });
        $(".trendy-product-slider").owlCarousel({
            loop: true,
            nav: false,
            margin: 20,
            dots: false,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            responsive: {
                0: {items: 2, },
                480: {items: 2, },
                750: {items: 4, },
                950: {items: 2, },
                1170: {items: 2, },
            }
        });
        /*Owl Carousel for blog*/
        $(".blog-slider").owlCarousel({
            loop: true,
            nav: true,
            margin: 30,
            dots: false,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            responsive: {
                0: {items: 1, },
                750: {items: 2, },
                1170: {items: 3, },
            }
        });

        $('.funfact').appear(function () {
            $('.timer').countTo({
                speed: 3000
            });
        });
        /*Owl Carousel for Testimonial*/
        $(".testimonial-slider").owlCarousel({
            items: 1,
            nav: true,
            margin: 30,
            dots: false,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
        });
        /*Owl Carousel for Brand*/
        $(".brand-slider").owlCarousel({
            nav: true,
            margin: 30,
            dots: false,
            navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
            responsive: {
                0: {items: 2, },
                750: {items: 4, },
                950: {items: 5, },
            }
        });
        /*----- Scroll Up -----*/
        $.scrollUp({
            scrollText: '<i class="fa fa-chevron-up"></i>',
            easingType: 'linear',
            scrollSpeed: 900,
            animation: 'fade'
        });

        $('#portfolio').mixItUp();

        /*-- Work PopUp --*/
        $('.port-wrap .hover').magnificPopup({
            type: 'image',
            gallery: {
                enabled: true
            },
            mainClass: 'mfp-with-zoom',
        });




        /*----- Check Out Accordion -----*/
        $(".panel-heading a").on("click", function () {
            $(".panel-heading a").removeClass("active");
            $(this).addClass("active");
        });
        /*----- Simple Lens -----*/
        $('.simpleLens-lens-image').simpleLens({
            loading_image: 'img/loading.gif'
        });

        $('.newslater-container .close').on("click", function () {
            $('#popup-newslater').addClass('hidden');
        });

       

    });

})(jQuery);


/*-----------------------Ajouter une notification-------------------------------------*/


/*
 * Ajouter une notification
 */
function addNotification(message, type) {
    var css_class = '';
    var icon = '';
    if (type === 'error') {
        css_class = 'danger';
        icon = '<i class="fa fa-times-circle" aria-hidden="true"></i>';
    } else if (type === 'notice') {
        css_class = 'warning';
        icon = '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>';
    } else if (type === 'success') {
        css_class = 'success';
        icon = '<i class="icon fa fa-check" ></i>';
    }
    var notification = '<br>'
            + '<section>'
            + '<div class="container" >'
            + '<div class="row" >'
            + '<div class="col-lg-12" >'
            + '<div class="alert alert-' + css_class + ' alert-dismissable">'
            + icon
            + '<button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>'
            + ' ' + message
            + '</div>'
            + '</div>'
            + '</div>'
            + '</div>'
            + '</section>';
    $("#notifications").html(notification);
    $("html, body").animate({scrollTop: 0}, "slow");
}

/*------------------------------------------------------------------------------*/