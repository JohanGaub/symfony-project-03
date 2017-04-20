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
