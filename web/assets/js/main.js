/**
 * Function to do dynamic topbar with scroll event
 */
$(document).ready(function () {

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

/**
 * Function to transform right to down arrow
 */
$(document).ready(function () {
    if ($(window).width() <= 1298) {
        $("#arrowbox").addClass("arrow-bottom-center");
    } else {
        $("#arrowbox").addClass("arrow-right-center");
    }

// Change class name in "html.twig" file in order to use a specific scss mixin
    $(window).on('resize', function() {
        if ($(window).width() <= 1298) {
            $("#arrowbox").removeClass("arrow-right-center").addClass("arrow-bottom-center");
        } else {
            $("#arrowbox").removeClass("arrow-bottom-center").addClass("arrow-right-center");
        }
    });
});

// Swiper Slider init and conf
$(document).ready(function () {
    let swiper = new Swiper('.swiper-container', {
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

// Modal front office part (mld)
$(document).ready(function () {
    let target = ".modal-fullscreen";
    let modal  = ".modal-backdrop";
    $(target).on('show.bs.modal', function () {
        setTimeout( function() {
            $(modal).addClass("modal-backdrop-fullscreen");
        }, 0);
    });
    $(target).on('hidden.bs.modal', function () {
        $(modal).addClass("modal-backdrop-fullscreen");
    });
});



$( function() {
    $( ".datepicker1" ).datepicker( {
        dateFormat: 'dd/mm/yy',
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
        dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
        weekHeader: 'Sem.',
        nextText: " Suiv.",
        prevText: "Préc. /"
    });
} );

$( function() {
    $( ".datepicker2" ).datepicker( {
        dateFormat: 'dd/mm/yy',
        monthNames: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
        monthNamesShort: ['Janv.', 'Févr.', 'Mars', 'Avril', 'Mai', 'Juin', 'Juil.', 'Août', 'Sept.', 'Oct.', 'Nov.', 'Déc.'],
        dayNames: ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'],
        dayNamesShort: ['Dim.', 'Lun.', 'Mar.', 'Mer.', 'Jeu.', 'Ven.', 'Sam.'],
        dayNamesMin: ['D', 'L', 'M', 'M', 'J', 'V', 'S'],
        weekHeader: 'Sem.',
        nextText: " Suiv.",
        prevText: "Préc. /"
    });
} );


$( function() {
    $('#table-index tr.highlight-index td').each(function() {
        if ($(this).text() == 'Haute') {
            $(this).closest('tr').css('background-color', '#f9d5c6');
        }
    });
} );
