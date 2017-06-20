/**
 * Add
 * Function to send new element in vue + db
 * View append many things in html
 * (Here idk why but i need to get temp storage of token
 * for rewrite him on token field)
 */
$(document).ready( function () {
    $('.dictionary-form').submit( function (e) {
        e.preventDefault()
        let $this       = $(this)
        let formType    = $this.prev().attr('id')
        let field       = $('.dictionary-form-' + formType + ' :input')
        let tokenTarget = $('.dictionary-token-' + formType)
        let tokenValue  = tokenTarget.val()
        let form        = $this.serialize()

        $.ajax({
            type: $this.attr('method'),
            url: '/dictionnaire/nouveau/' + formType,
            data: form,
            dataType: 'json',
            timeout: 3000,
            success: function (data) {
                let elementId = data.element
                let elementValue = data.value
                if (data.status === 'succes') {
                    // add full li with value + button to update && delete
                    let domSend = '<li id="list-group-item-' + elementId + '" class="list-group-item" data-index-number="' + elementId + '">'
                    domSend += '<span id="li-value-' + elementId + '">' + elementValue + '</span>'
                    domSend += '<span class="badge">'
                    domSend += '<a class="modal-update" href="" data-toggle="modal" data-target="#dictionary-modal-update">'
                    domSend += '<i class="fa fa-cog" aria-hidden="true"></i></a><span> </span>'
                    domSend += '<a class="modal-delete" href="" data-toggle="modal" data-target="#dictionary-modal-delete">'
                    domSend += '<i class="fa fa-close" aria-hidden="true"></i></a>'
                    domSend += '</span></li>'
                    $('#' + formType).append(domSend)
                    $('#form_' + formType).val('')
                    field.val('')
                } else if (data.status === 'error'){
                    let target = '#' + formType
                    /** function to add error msg element (see under !) */
                    addMsgError(target, data.status, formType, data.element)
                    /** function to remove error msg element (see under !) */
                    hideMsgError($('.list-group-item-' + data.status + '-' + formType))
                }
                /** Rewrite token here ! */
                tokenTarget.val(tokenValue)
            },
        })
    })
})

/**
 * Update
 * Function call information in modal (form)
 * After we can confirm the update with submit
 * && get new view
 */
$(document).ready(function () {
    let updateField = $('.dictionary-update-field')
    let listElementId = ''
    let currentValue = ''
    let currentId = ''
    let inputForm = ''
    let type = ''

    $(".modal-update").click(function (e) {
        e.preventDefault()
        let $this       = $(this)
        currentId       = $($this).parent().parent().attr('data-index-number')
        listElementId   = $this.parent().parent().attr('id')
        currentValue    = $('#li-value-' + currentId).text().trim()
        type            = $this.parent().parent().parent().attr('id')
        $(updateField).val(currentValue)
        console.log('test => ' + $(listElementId).attr('data-index-number'))
    })

    $('.dictionary-update-form').submit(function (e) {
        e.preventDefault()
        let newValue    = $(updateField).val()
        let form        = $(this).serialize()

        if (newValue === '' || newValue === currentValue) {
            // if field is empty or no change are detected
        } else {
            $.ajax({
                type: 'POST',
                url: '/dictionnaire/modification/' + currentId,
                data: form,
                dataType: 'json',
                timeout: 3000,
                success: function (data) {
                    if (data.status === 'succes') {
                        // change content of span who have current value
                        $("#li-value-" + currentId).text(newValue)
                        $('#dictionary-modal-update').modal('hide');
                        $(inputForm).val('')
                    } else if (data.status === 'error') {
                        /** function to add error msg element (see under !) */
                        addMsgError('#modal-update-msg', data.status, type, data.element, currentId)
                        let hideTarget = $('.msg-return-' + type).parent().parent()
                        $(hideTarget).css('transition', '1s ease')
                        /** function to remove error msg element (see under !) */
                        hideMsgError($(hideTarget))
                    }
                },
            })
        }
    })
})

/**
 * Delete
 * Function to open a modal and send in informations
 * After can confirm delete action with click on link
 */
$(document).ready( function () {
    let listElement = ''
    let listElementId = ''
    let listElementValue = ''
    let fieldValueId = ''
    let id = ''
    let type = ''

    $(".modal-delete").click(function (e) {
        /**
         * Disable normal form event
         * && get id from li for delete by id
         */
        e.preventDefault()
        let $this           = $(this)
        listElement         = $this.parent().parent()
        listElementId       = listElement.attr('id')
        listElementValue    = listElement.text().trim()
        fieldValueId        = $('#' + listElementId).children('span').attr('id')
        type                = listElement.parent().attr('id')
        id                  = listElement.attr('data-index-number')
        $("#dictionary-delete-value").text(listElementValue)
    })

    $("#dictionary-link-delete").click(function (e) {
        /**
         * Disable normal form event
         * && get id from li for delete by id
         */
        e.preventDefault()

        $.ajax({
            type: 'GET',
            url: '/dictionnaire/suppression/' + id,
            timeout: 3000,
            success: function (data) {
                if (data.status === 'succes') {
                    $('#' + listElementId).children('.badge').remove()
                    let target = '#' + fieldValueId
                    $(target).text('Entrée supprimée')
                    setTimeout( function () {
                        $(target).css('transition', '2s ease-out').css('opacity', 0)
                            setTimeout( function () {
                                $(target).parent().remove()
                            }, 2000)
                    }, 3000)
                } else if (data.status === 'error') {
                    $('#' + listElementId + ' span').css('display', 'none')
                    /** function to add error msg element (see under !) */
                    let target = $('#' + listElementId)
                    addMsgError(target, data.status, type, data.element)
                    /** function to remove error msg element (see under !) */
                    target = $('.list-group-item-error-' + type)
                    hideMsgError(target)
                    setTimeout( function () {
                        $('#' + listElementId + ' span').css('display', 'inline-block')
                    }, 6000)
                }
            },
        })
    })
})

/**
 * Add error message
 *
 * @param target
 * @param status
 * @param formType
 * @param value
 * @param id
 */
function addMsgError(target, status, formType, value, id)
{
    let domSend = '<li class="list-group-item list-group-item-' + status + '-' + formType + '" data-index-number="' + id + '">'
    domSend += '<span>'
    domSend += '<p class="msg-return msg-return-' + formType + '">' + value + '</p>'
    domSend += '</span></li>'
    $(target).append(domSend)
}
/**
 * Remove error message
 *
 * @param target
 */
function hideMsgError(target)
{
    setTimeout( function () {
        target.css('opacity', '0');
        setTimeout( function () {
            target.remove()
        }, 1000)
    }, 5000)
}
