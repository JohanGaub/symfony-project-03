/**
 * Add new comment
 * TODO => Fix date echo need good format
 */
$(document).ready( function () {
    let formId  = '#app_bundle_comment_userTechnicalEvolution'
    let fieldId = '#app_bundle_comment_userTechnicalEvolution_comment'
    let id      = $(formId).attr('data-index-number')

    $(formId).submit( function (e) {
        e.preventDefault()
        let newCommentValue = $(fieldId).val()

        $.ajax({
            type: 'POST',
            url: '/evolution-technique/commentaires/ajout/' + id,
            data : {
                'data': newCommentValue
            },
            dataType: 'json',
            timeout: 3000,
            success: function(data){
                let uteId   = data['id']
                let user    = data['user']
                let comment = data['comment']
                let date    = new Date(data['date']['date'])
                $('.comment-list').prepend(createComment(uteId, user, date, comment))
                $(fieldId).val('')
            },
        })
    })
})

/**
 * Listen user screen to load new comments
 */
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
                url: '/evolution-technique/commentaires/chargement/' + id,
                data: {
                    'data': nbElements
                },
                timeout: 100000,
                success: function(data){
                    setTimeout(function () {
                        $(data).each(function (key, values) {
                            let uteId   = values['id']
                            let user    = values['user']['userProfile']['firstname']
                                user    += ' ' + values['user']['userProfile']['lastname']
                            let date    = (new Date(values['date']['date']))
                            let comment = values['comment']
                            //let date    = new Date(values['ute_date']['date'])
                            $('.comment-list').append(createComment(uteId, user, date, comment))

                            // TODO => Find a solution if last data length is equal to 10
                            if (data.length < 10){
                                $(loader).hide(function () {
                                    clearInterval(interval)
                                })
                            }
                            status = false;
                        })
                    } ,3000)
                },
            })
        }
    }, 500)
})

/**
 * Delete comment
 * TODO => Fix probleme with chain delete
 */
$(document).ready( function () {
    $('.modal-delete').click( function () {
        let $this           = $(this)
        let commentFullId   = $this.parent().attr('id')
        let commentId       = commentFullId.replace('ute_id_', '')
        let commentValue    = $this.parent().children($('.comment-value'))[2]['outerText']

        $('.delete-value').replaceWith('<p class="delete-value">' + commentValue + '</p>')

        $('#comment-link-delete').click( function (e) {
            e.preventDefault()

            $.ajax({
                type: 'GET',
                url: '/evolution-technique/commentaires/suppression/' + commentId,
                timeout: 3000,
                success: function(){
                    let elementList = '<div class="unit-comment">'
                        elementList += '<h5>Commentaire supprimé<h5>'
                        elementList += '</div>'
                    $('#' + commentFullId).replaceWith(elementList)
                },
            })
        })
    })
})

/**
 * Update action
 * TODO => Need to fix multi upload ?? :(
 * TODO => Find solution to upload dynamic loading comments (delete to)
 */
$(document).ready( function () {
    $('.modal-update').click( function (e) {
        let $this = $(this)
        let commentFullId   = $this.parent().attr('id')
        let commentId       = commentFullId.replace('ute_id_', '')
        let commentValue    = $this.parent().children($('.comment-value'))[2]['outerText']
        let commentForm     = '#app_bundle_comment_userTechnicalEvolution_update'
        let commentField    = '.app_bundle_comment_userTechnicalEvolution_updateField'

        $(commentField).val(commentValue)

        $(commentForm).submit( function (e) {
            e.preventDefault()
            let newComment = $(commentField).val()

            if(newComment !== commentValue) {
                $.ajax({
                    type: 'POST',
                    url: '/evolution-technique/commentaires/modification/' + commentId,
                    data: {
                        'data': newComment
                    },
                    dataType: 'json',
                    timeout: 3000,
                    success: function(){
                        let textId = 'comment-value-id-' + commentId
                        let newContent = '<p id="' + textId + '" class="comment-value">'
                            newContent += newComment
                            newContent += '</p>'
                        $('#' + textId).replaceWith(newContent)
                    },
                })
            }
        })
    })
})

/**
 * Star rating system
 */
$(document).ready( function () {
    $('.star-link').click( function () {
        let $this       = $(this)
        let valueVote   = $this.attr('value')
        let evoId       = $('.star-rating').attr('data-index-number')

        // => TODO need to finish ajax request for vote here
        $.ajax({
            type: 'POST',
            url: '/evolution-technique/notes/ajout/' + evoId,
            data: {
                'data': valueVote
            },
            dataType: 'json',
            timeout: 3000,
            success: function(){

            },
        })
    })
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


function createComment(uteId, user, date, comment)
{
    let elementList = '<div id="ute_id_' + uteId + '" class="unit-comment">'
        elementList += '<h5>' + user + '</h5>'
        elementList += '<i>Le ' + date + '</i>'
        elementList += '<p id="comment-value-id-' + uteId + '" class="comment-value">' + comment + '</p>'
        elementList += '<a class="modal-update" href="" data-toggle="modal" '
        elementList += 'data-target="#comment-modal-update" data-index-number="' + uteId + '">'
        elementList += '<i class="fa fa-cog" aria-hidden="true"></i>'
        elementList += '</a>'
        elementList += '<span> </span>'
        elementList += '<a class="modal-delete" href="" data-toggle="modal" '
        elementList += 'data-target="#comment-modal-delete" data-index-number="' + uteId + '">'
        elementList += '<i class="fa fa-close" aria-hidden="true"></i>'
        elementList += '</a>'
        elementList += '</div>'
    return elementList;
}