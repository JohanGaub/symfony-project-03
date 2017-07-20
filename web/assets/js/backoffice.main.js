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
