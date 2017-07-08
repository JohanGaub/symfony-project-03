/**
 * Star rating system
 */
$(document).ready(function () {
    $(init);
    function init() {
        $('.star-link').unwrap().unwrap() // delete div and label that are created by symfony around input when using bootstrap template
    }
});

let DisplayRating = function($el) {
    $el.addClass('on').prevAll().addClass('on');
    $el.nextAll().removeClass('on');
};

$('.controls.rating')
    .addClass('starRating')
    .on('mouseenter', 'label', function() {
        if($(this).parent().is('div'))
            DisplayRating($(this));
    })
    .on('mouseleave', function() {
        let $this = $(this);
        $selectedRating = $this.find('input:checked');

        if ($selectedRating.length === 1) {
            if ($selectedRating.parent().is('div'))
                DisplayRating($selectedRating.parent());

        } else {
            $this.find('on').removeClass('on');
        }
    })


$(document).ready(function() {
    $('.label-star-link').hover(function () {
        $('.rating-value').text($(this).attr('data-index-number'))
    });
    let form = $('#app_bundle_note_userTechnicalEvolution');
    $('.star-link').click(function () {
        let $this = $(this);
            $this.find('input:checked').val();

        if ($(this).is(':checked')) {
            $(form).submit();
        }
    })

    $(form).submit(function(){
        let evoId       = $('.controls.rating').attr('data-index-number')
        let form        = $('#app_bundle_note_userTechnicalEvolution').serialize()

        let viewTemplate   = '<p class="valid-message">'
        viewTemplate       += 'Votre vote à bien été enregistrée !'
        viewTemplate       += '</p>'
        $('.error-box').append(viewTemplate)
        setTimeout( function () {
            let target = '.valid-message'
            $(target).css('opacity', '0')
            setTimeout( function () {
                $(target).hide()
            }, 30000)
        }, 50000)

        $.ajax({
            type: 'POST',
            url: '/evolution-technique/notes/ajout/' + evoId,
            data: form,
            dataType: 'json',
            timeout: 3000,
            success: function(data){
            },
        })
    });
});


/**
 * Listen user screen to load new comments
 */
$(document).ready( function () {
    let loaderDom = '<div class="loader">'
    loaderDom += '<div class="inner one"></div>'
    loaderDom += '<div class="inner two"></div>'
    loaderDom += '</div>'
    let status = false;
    let loader = '.loader-wcs'
    $(loader).append(loaderDom);

    /**
     * Every 0.5s we need if user see the loader
     * After we send new comments he need by 10
     * if comment result is under than 10 we
     * stop interval (stop function)
     */
    let interval = setInterval(function(){
        if (isScrolledIntoView($(loader)) && !status) {
            status          = true;
            let id          = $(loader).attr('data-index-number')
            let nbElements  = $('.unit-comment').length;

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
                            user        += ' ' + values['user']['userProfile']['lastname']
                            let date    = (new Date(values['date']['date']))
                            let comment = values['comment']
                            $('.comment-list').append(createComment(uteId, user, date, comment))

                            /**
                             * Verification about nb result request return
                             * stop watcher if he is under than 10
                             */
                            if (data.length < 10){
                                $(loader).hide(function () {
                                    clearInterval(interval)
                                })
                            }
                            status = false;
                        })
                    } ,150)
                },
                error: function () {
                    $(loader).hide(function () {
                        clearInterval(interval)
                    })
                }
            })
        }
    }, 500)
})

/**
 * Add new comment
 */
$(document).ready( function () {
    let formId  = '#app_bundle_comment_userTechnicalEvolution'
    let fieldId = '#app_bundle_comment_userTechnicalEvolution_comment'
    let id      = $(formId).attr('data-index-number')

    $(formId).submit( function (e) {
        e.preventDefault()
        let form = $(this).serialize()

        $.ajax({
            type: 'POST',
            url: '/evolution-technique/commentaires/ajout/' + id,
            data : form,
            dataType: 'json',
            timeout: 3000,
            success: function(data){
                let date = new Date(data['date']['date'])
                $('.comment-list').prepend(createComment(data['id'], data['user'], date, data['comment']))
                $(fieldId).val('')
            },
        })
    })
})

/**
 * Delete comment
 */
$(document).ready( function () {
    let commentFullId   = ''
    let commentId       = ''
    let commentValue    = ''

    $(document).on('click', '.modal-delete', function () {
        let $this           = $(this)
        commentFullId       = $this.parent().attr('class')
        commentId           = $this.attr('data-index-number')
        commentValue        = $('#comment-value-id-' + commentId).text()
        $('.delete-value').replaceWith('<p class="delete-value">' + commentValue + '</p>')
    })

    $('#comment-link-delete').click( function (e) {
        e.preventDefault()
        $.ajax({
            type: 'GET',
            url: '/evolution-technique/commentaires/suppression/' + commentId,
            timeout: 3000,
            success: function() {
                let elementList = '<div class="unit-comment">'
                elementList += '<h5>Commentaire supprimé<h5>'
                elementList += '</div>'
                $('#ute_id_' + commentId).replaceWith(elementList)
            },
        })
    })
})

/**
 * Update action
 */
$(document).ready( function () {
    let commentField    = '.app_bundle_comment_userTechnicalEvolution_updateField'
    let commentForm     = '#app_bundle_comment_userTechnicalEvolution_update'
    let commentFullId   = ''
    let commentId       = ''
    let commentValue    = ''

    $(document).on('click', '.modal-update', function () {
        let $this       = $(this)
        commentFullId   = $this.parent().parent().attr('id')
        commentId       = commentFullId.replace('ute_id_', '')
        commentValue    = $this.parent().children($('.comment-value'))[2]['outerText']
        $(commentField).val(commentValue)
    })

    $(commentForm).submit( function (e) {
        e.preventDefault()
        let newComment  = $(commentField).val()
        let form        = $(this).serialize()

        if(newComment !== commentValue) {
            $.ajax({
                type: 'POST',
                url: '/evolution-technique/commentaires/modification/' + commentId,
                data: form,
                dataType: 'json',
                timeout: 3000,
                success: function(){
                    let textId = 'comment-value-id-' + commentId
                    let newContent = '<p id="' + textId + '" class="comment-value">'
                    newContent += newComment
                    newContent += '</p>'
                    $('#' + textId).replaceWith(newContent)
                    $('#comment-modal-update').modal('hide')
                },
            })
        }
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

/**
 * Add new Comments to DOM
 *
 * @param uteId
 * @param user
 * @param date
 * @param comment
 * @returns {string}
 */
function createComment(uteId, user, date, comment) {
    let elementList = '<div id="ute_id_' + uteId + '" class="unit-comment">'
    elementList += '<h5>Par <span class="strong-green">' + user + '</span></h5>'
    elementList += '<i>Le ' + date + '</i>'
    elementList += '<p id="comment-value-id-' + uteId + '" class="comment-value">' + comment + '</p>'
    elementList += '<div class="be-flex space-between">'
    elementList += '<a class="modal-update" href="" data-toggle="modal" '
    elementList += 'data-target="#comment-modal-update" data-index-number="' + uteId + '">'
    elementList += 'Modifier le commentaire'
    elementList += '</a>'
    elementList += '<a class="modal-delete" href="" data-toggle="modal" '
    elementList += 'data-target="#comment-modal-delete" data-index-number="' + uteId + '">'
    elementList += 'Supprimer le commentaire'
    elementList += '</a>'
    elementList += '</div>'
    elementList += '</div>'
    return elementList;
}