$(document).ready( function () {
    let loaderDom = '<div class="loader">'
        loaderDom += '<div class="inner one"></div>'
        loaderDom += '<div class="inner two"></div>'
        loaderDom += '<div class="inner three"></div>'
        loaderDom += '<div class="inner four"></div>'
        loaderDom += '</div>'
    let loader = '.loader-wcs'
    let status = false;
    $(loader).append(loaderDom);

    /**
     * Every 0.5s we need if user see the loader
     * After we send new comments he need by 10
     * if comment result is under than 10 we
     * stop interval (stop function)
     */
    let interval = setInterval(function(){
        if (isScrolledIntoView($(loader)) && !status) {
            status = true;
            let id = $(loader).attr('data-index-number')
            let nbElements = $('.unit-comment').length;

            $.ajax({
                type: 'POST',
                url: '/evolution-technique/commentaires-chargement/' + id,
                data: {
                    'data': nbElements
                },
                timeout: 3000,
                success: function(data){
                    setTimeout(function () {
                        $(data).each(function (key, values) {
                            let user    = values['up_firstname'] + ' ' + values['up_lastname']
                            let comment = values['ute_comment']

                            let elementList = '<div class="unit-comment">'
                                elementList += '<h5>' + user + '<h5>'
                                elementList += '<p>' + comment + '</p>'
                                elementList += '</div>'
                            $('.comment-list').append(elementList)

                            if (data.length < 10){
                                $(loader).hide(function () {
                                    clearInterval(interval)
                                })
                            }
                            status = false;
                        })
                    } ,1000)
                },
            })
        }
    }, 500)
})

/**
 * Know if loader is in view
 *
 * @param elem
 * @returns {boolean}
 */
function isScrolledIntoView(elem)
{
    let docViewTop      = $(window).scrollTop();
    let docViewBottom   = docViewTop + $(window).height();
    let elemTop         = $(elem).offset().top;
    let elemBottom      = elemTop + $(elem).height();

    return ((elemBottom <= docViewBottom) && (elemTop >= docViewTop));
}
