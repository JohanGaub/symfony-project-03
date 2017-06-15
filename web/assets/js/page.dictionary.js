
/**
 * Add
 * Function to send new element in vue + db
 * View append many things in html
 */
$(document).ready( function () {
    $('.dictionary_form').submit( function (e) {
        e.preventDefault()
        let $this       = $(this)
        let data        = {}
        data['type']    = $this.children().children().attr('data-index-number')
        data['value']   = $this.children().children().val()

        if (data['value'] === '') {
            // if field is empty
        } else {
            $.ajax({
                type: $this.attr('method'),
                url: '/dictionnaire/nouveau',
                data: {
                    'data': data
                },
                dataType: 'json',
                timeout: 3000,
                success: function (result) {
                    // add full li with value + button to update && delete
                    let domSend = '<li id="list_group_item_' + result.id + '" class="list-group-item">'
                    domSend += '<span id="li_value_' + result.id + '">' + data['value'] + '</span>'
                    domSend += '<span class="badge">'
                    domSend += '<a class="modal-update" href="" data-toggle="modal" data-target="#dictionary-modal-update">'
                    domSend += '<i class="fa fa-cog" aria-hidden="true"></i></a>'
                    domSend += '<span> </span>'
                    domSend += '<a class="modal-delete" href="" data-toggle="modal" data-target="#dictionary-modal-delete">'
                    domSend += '<i class="fa fa-close" aria-hidden="true"></i></a>'
                    domSend += '</span></li>'
                    $('#' + data['type']).append(domSend)
                },
            })
        }
    })
})

/**
 * Update
 * Function call information in modal (form)
 * After we can confirm the update with submit
 * && get new view
 */
$(document).ready(function () {
    let listElementId = ''
    let listElementValue = ''
    let id = ''
    let value = ''
    let inputForm = ''

    $("#dictionarys-list ul li span a.modal-update").click(function (e) {
        e.preventDefault()
        listElementId       = $(this).parent().parent().attr('id')
        listElementValue    = $(this).parent().parent().text()
        id                  = listElementId.replace('list_group_item_', '')
        value               = listElementValue.trim()
        inputForm           = '#dictionary_input_update'
        // TODO => Find why i can't send many times content to input form ???
        $(inputForm).attr('value', value)
    })

    $('#dictionary_form_update').submit(function (e) {
        e.preventDefault()
        let newValue = $(inputForm).val()

        if (newValue === '' || newValue === value) {
            // if field is empty or no change are detected
        } else {
            $.ajax({
                type: 'POST',
                url: '/dictionnaire/modification/' + id,
                data: {
                    'data': newValue
                },
                dataType: 'json',
                timeout: 3000,
                success: function () {
                    // change content of span who have current value
                    $("#li_value_" + id).text(newValue)
                    $('#dictionary-modal-update').modal('hide');
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
    let listElementId = ''
    let listElementValue = ''
    let id = ''
    let value = ''

    $("#dictionarys-list ul li span a.modal-delete").click(function (e) {
        /**
         * Disable normal form event
         * && get id from li for delete by id
         */
        e.preventDefault()
        listElementId       = $(this).parent().parent().attr('id')
        listElementValue    = $(this).parent().parent().text()
        id                  = listElementId.replace('list_group_item_', '')
        value               = listElementValue.trim()
        $("#dictionary_delete_value").text(value)
    })

    $("#dictionary_link_delete").click(function (e) {
        console.log(id)
        /**
         * Disable normal form event
         * && get id from li for delete by id
         */
        e.preventDefault()
        let elementId = 'list_group_item_' + id

        $.ajax({
            type: 'GET',
            url: '/dictionnaire/suppression/' + id,
            timeout: 3000,
            success: function () {
                // delete the target element
                $("#" + elementId).remove()
            },
        })
    })
})
