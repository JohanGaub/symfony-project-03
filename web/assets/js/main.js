$(document).ready(function () {


    // animate navbar on scroll
    $(window).scroll(function () {

        if ($(document).scrollTop() > 100) {
            $('.navbar, .navbar-default .navbar-brand, .navbar-brand img, .navbar-collapse').addClass('scroll');
        } else {
            $('.navbar, .navbar-default .navbar-brand, .navbar-brand img, .navbar-collapse').removeClass('scroll');
        }

    });

    $(window).scroll(function () {

        if ($(document).scrollTop() > 80) {
            $('.navbar, .navbar-default .navbar-brand, .navbar-brand img, .navbar-collapse').addClass('scroll-midd');
        } else {
            $('.navbar, .navbar-default .navbar-brand, .navbar-brand img, .navbar-collapse').removeClass('scroll-midd');
        }

    });

    $(window).scroll(function () {

        if ($(document).scrollTop() > 50) {
            $('.navbar, .navbar-default .navbar-brand, .navbar-brand img, .navbar-collapse, div.navbar-header.header').addClass('scroll-small');
        } else {
            $('.navbar, .navbar-default .navbar-brand, .navbar-brand img, .navbar-collapse, div.navbar-header.header').removeClass('scroll-small');
        }
    });
});





$(document).ready(function () {
        if ($(window).width() <= 767) {
            $("#arrowbox").addClass("arrow-bottom-center");
        } else {
            $("#arrowbox").addClass("arrow-right-center");
        }




// Change class name in "html.twig" file in order to use a specific scss mixin
    $(window).on('resize', function() {
        if ($(window).width() <= 767) {
            $("#arrowbox").removeClass("arrow-right-center").addClass("arrow-bottom-center");
        } else {
            $("#arrowbox").removeClass("arrow-bottom-center").addClass("arrow-right-center");
        }
    });
});


// Swiper Slider
$(document).ready(function () {
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        paginationClickable: true,
        spaceBetween: 20,
        centeredSlides: true,
        autoplay: 60000,
        loop: true,
        autoplayDisableOnInteraction: false
    });
});



// Modal
$(document).ready(function () {
    $(".modal-fullscreen").on('show.bs.modal', function () {
        setTimeout( function() {
            $(".modal-backdrop").addClass("modal-backdrop-fullscreen");
        }, 0);
    });
    $(".modal-fullscreen").on('hidden.bs.modal', function () {
        $(".modal-backdrop").addClass("modal-backdrop-fullscreen");
    });
});


// Swiper Slider
$(document).ready(function () {
    var swiper = new Swiper('.swiper-container', {
        pagination: '.swiper-pagination',
        nextButton: '.swiper-button-next',
        prevButton: '.swiper-button-prev',
        paginationClickable: true,
        spaceBetween: 20,
        centeredSlides: true,
        autoplay: 60000,
        loop: true,
        autoplayDisableOnInteraction: false
    });
});

