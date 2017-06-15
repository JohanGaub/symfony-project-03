// TODO => Find a solution for news add (update && delete) is not send
$(document).ready(function () {
    // loop on array to init different ajax form
    for (let id of formId) {
        formSubmitListening(id)
    }
    // update action for all page list
    updateModalList()
    // delete action for all page list
    deleteModalList()
})

/**
 * Data type, should be same than db type
 * @type {[*]}
 */
let formId = [
    'category_type',
    'technical_evolution_status',
    'technical_evolution_origin',
]

/**
 * formSubmitListener
 * Function to send new element in vue + db
 * View append many things in html
 *
 * @param formId
 */
function formSubmitListening(formId) {
    $("#dictionary_form_" + formId).submit(function (e) {
        /**
         * Disable normal form event
         * && get id and value
         */
        e.preventDefault()
        let $this = $(this)
        let data = {}
        data['value'] = $('#value_' + formId).val()
        data['type'] = $this.attr('id')

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
                    let list = "#list_" + formId
                    let domSend = '<li id="list_group_item_' + result.id + '" class="list-group-item">'
                    domSend += '<span id="li_value_' + result.id + '">' + data['value'] + '</span>'
                    domSend += '<span class="badge">'
                    domSend += '<a class="modal-update" href="" data-toggle="modal" data-target="#dictionary-modal-update">'
                    domSend += '<i class="fa fa-cog" aria-hidden="true"></i></a>'
                    domSend += '<span> </span>'
                    domSend += '<a class="modal-delete" href="" data-toggle="modal" data-target="#dictionary-modal-delete">'
                    domSend += '<i class="fa fa-close" aria-hidden="true"></i></a>'
                    domSend += '</span></li>'
                    $(list).append(domSend)
                },
            })
        }
    })
}

/**
 * Function call information in modal (form)
 * After we can confirm the update with submit
 * && get new view
 * TODO => Find a solution to get many update without reload page
 */
function updateModalList() {
    $("#dictionarys-list ul li span a.modal-update").click(function (e) {
        e.preventDefault()
        let listElementId = $(this).parent().parent().attr('id')
        let listElementValue = $(this).parent().parent().text()
        let id = listElementId.replace('list_group_item_', '')
        let value = listElementValue.trim()
        let inputForm = '#dictionary_input_update'
        // TODO => Find why this text replace at all click don't work
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
}

/**
 * Function to open a modal and send in informations
 * After can confirm delete action with click on link
 */
function deleteModalList() {
    $("#dictionarys-list ul li span a.modal-delete").click(function (e) {
        /**
         * Disable normal form event
         * && get id from li for delete by id
         */
        e.preventDefault()
        let listElementId = $(this).parent().parent().attr('id')
        let listElementValue = $(this).parent().parent().text()
        let id = listElementId.replace('list_group_item_', '')
        let value = listElementValue.trim()

        console.log('id -> ' + id + '|| v -> ' + value)

        $("#dictionary_delete_value").text(value)

        $("#dictionary_link_delete").click(function (e) {

            /**
             * Disable normal form event
             * && get id from li for delete by id
             */
            e.preventDefault()
            let listElementId = 'list_group_item_' + id

            $.ajax({
                type: 'GET',
                url: '/dictionnaire/suppression/' + id,
                timeout: 3000,
                success: function () {
                    // delete the target element
                    $("#" + listElementId).remove()
                },
            })
        })
    })
}
