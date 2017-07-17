/**
 * Sidebar control parts
 */
function htmlbodyHeightUpdate(){
    let height1 = $('.nav').height()+50
    let height2 = $('.main').height()
    let height3 = $( window ).height()

    if (height2 > height3) {
        $('html').height(Math.max(height1,height3,height2)+10);
        $('body').height(Math.max(height1,height3,height2)+10);
    } else {
        $('html').height(Math.max(height1,height3,height2));
        $('body').height(Math.max(height1,height3,height2));
    }
}
$(document).ready(function () {
    htmlbodyHeightUpdate()
    $(window).resize(function() {
        htmlbodyHeightUpdate()
    });
    $(window).scroll(function() {
        htmlbodyHeightUpdate()
    });

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
