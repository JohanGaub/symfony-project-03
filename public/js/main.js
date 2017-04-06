$("#slider1").responsiveSlides({
    speed: 400,
    timeout: 8000,
    nav: true,
    prevText: "<span class='glyphicon glyphicon-chevron-left'></span>",
    nextText: "<span class='glyphicon glyphicon-chevron-right'></span>"
});

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
