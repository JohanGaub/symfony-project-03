/**
 * Sidebar control parts
 */
$(document).ready(function () {
    let focus       = '.sidebar'
    let target      = 'header .dropdown'
    let cssClass    = 'open'

    $(target).mouseenter(function () {
        $(this).addClass(cssClass)
    })

    $(focus).mouseleave(function () {
        $(target).removeClass(cssClass)
    })
});

/**
 * Datepikers
 */
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

/**
 * Hightlight emergency high
 */
$( function() {
    $('#table-index tr.highlight-index td').each(function() {
        if ($(this).text() == 'Haute') {
            $(this).closest('tr').css('background-color', '#f9d5c6');
        }
    });
} );
