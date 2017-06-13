$(document).ready( function () {

    let loaderDom = '<div class="loader">'
        loaderDom += '<div class="inner one"></div>'
        loaderDom += '<div class="inner two"></div>'
        loaderDom += '<div class="inner three"></div>'
        loaderDom += '<div class="inner four"></div>'
        loaderDom += '</div>'

    let loader = '.loader-wcs'
    $(loader).append(loaderDom);

    let status = false;

    setInterval( function () {
        if (isScrolledIntoView($(loader)) && !status) {
            status = true;
            console.log('In View')

        }
    }, 500)


})

function isScrolledIntoView(elem)
{
    let docViewTop = $(window).scrollTop();
    let docViewBottom = docViewTop + $(window).height();

    let elemTop = $(elem).offset().top;
    let elemBottom = elemTop + $(elem).height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}