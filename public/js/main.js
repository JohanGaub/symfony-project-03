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
        if (screen.width >= 1024) {
            if ($(document).scrollTop() > 100) {
                $('.navbar, .navbar-default .navbar-brand, .navbar-brand img, .navbar-collapse').addClass('scroll');
            } else {
                $('.navbar, .navbar-default .navbar-brand, .navbar-brand img, .navbar-collapse').removeClass('scroll');
            }
        }
    });
});
