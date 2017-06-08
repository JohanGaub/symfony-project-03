$( document ).ready(function() {
    // loop on array to init different ajax form
    for (let id of formId) {
        formSubmitListening(id)
    }
    // update action for all page list
    updateModalList()
    // delete action for all page list
    deleteLiFromList()
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
 * @param formId
 */
function formSubmitListening(formId)
{
    $("#dictionary_form_" + formId).submit( function (e) {
        /**
         * Disable normal form event
         * && get id and value
         */
        e.preventDefault()
        let $this   = $(this)
        let value   = $('#value_' + formId).val()
        let type    = $(this).attr('id')

        if (value === ''){
            // if field is empty
        } else {
            $.ajax({
                type: $this.attr('method'),
                url: '/dictionnaire/nouveau',
                data : {
                    'dataForm': value,
                    'dataType': type
                },
                dataType: 'json',
                timeout: 3000,
                success: function(result){
                    // add full li with value + button to update && delete
                    let list = "#list_" + formId
                    $(list).append('<li id="list_group_item_' + result.id + '" class="list-group-item"><span id="li_value_' + result.id + '">' + value + '</span><span class="badge"><a class="update" href="/dictionnaire/modification/' + result.id + '"><i class="fa fa-cog" aria-hidden="true"></i></a><a class="delete" href="/dictionnaire/suppression/' + result.id + '"><i class="fa fa-close" aria-hidden="true"></i></a></span></li>')
                    e.preventDefault()
                },
            })
        }
    })
}

/**
 * updateModalList
 * Function call information in modal
 */
function updateModalList()
{
    $("#dictionarys-list ul li span a.update").click( function (e) {
        /**
         * Disable normal form event
         * && get id from li for update by id
         */
        e.preventDefault()
        let listElementId       = $(this).parent().parent().attr('id')
        let listElementValue    = $(this).parent().parent().text()
        let dbElementId         = listElementId.replace('list_group_item_', '')
        let dbElementValue      = listElementValue.trim()


        updateLiFromList(dbElementId, dbElementValue)
    })
}

/**
 * updateFormList
 *
 * Function to refresh new name list after request
 * @param id
 * @param value
 */
function updateLiFromList(id, value)
{
    let inputForm   = '#dictionary_input_update'
    let fullForm    = '#dictionary_form_update'

    $(inputForm).attr('value', value)

    $(fullForm).submit( function (e) {
        e.preventDefault()
        let newValue = $(inputForm).val()

        if (newValue === '' || newValue === value) {
            // if field is empty or no change are detected
        } else {
            $.ajax({
                type: 'POST',
                url: '/dictionnaire/modification/' + id,
                data : {
                    'data': newValue
                },
                dataType: 'json',
                timeout: 3000,
                success: function(){
                    // change content of span who have current value
                    let focusLi = '#li_value_' + id;
                    $(focusLi).replaceWith(newValue)
                },
            })
        }
    })
}

/**
 * Function to delete li from list and db
 */
function deleteLiFromList()
{
    $("#dictionarys-list ul li span a.delete").click( function (e) {
        /**
         * Disable normal form event
         * && get id from li for delete by id
         */
        e.preventDefault()
        let $this = $(this)
        let listElementId   = $this.parent().parent().attr('id')
        let dbElementId     = listElementId.replace('list_group_item_', '')

        $.ajax({
            type: 'GET',
            url: '/dictionnaire/suppression/' + dbElementId,
            timeout: 3000,
            success: function(){
                // delete the target element
                $("#" + listElementId).remove()
            },
        })
    })
}


