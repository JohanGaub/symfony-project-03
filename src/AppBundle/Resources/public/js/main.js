// Change class name in "html.twig" file in order to use a specific scss mixin
function redimensionnement() {
    var result = document.getElementById('result');
    if("matchMedia" in window) { // Détection
        if(window.matchMedia("(max-width:768px)").matches) {

            // There is less place
            $( "#arrowbox" ).removeClass( "arrow-bottom-center" ).addClass( "arrow-right-center" );
        } else {
            // The is more...
            $( "#arrowbox" ).removeClass( "arrow-right-center" ).addClass( "arrow-bottom-center" );
        }
    }
}
redimensionnement();


$( "div" ).click(function() {
    $( this ).switchClass( "big", "blue", 1000, "easeInOutQuad" );
});


// We link the event "resize" to the function
window.addEventListener('resize', redimensionnement, false);


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
