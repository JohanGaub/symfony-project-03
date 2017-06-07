$( document ).ready(function() {
    // loop on array to init different ajax form
    for (let id of formId) {
        formSubmitListening(id)
        deleteLastLiList(id)
    }
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
        let $this = $(this)
        let value = $('#value_' + formId).val()
        let type = $(this).attr('id')

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
                    $("#list_" + formId).append('<li id="list_item_' + result.id + '" class="list-group-item">' + value + '<span class="badge"><a href="/dictionnaire/suppression/' + result.id + '"><i class="fa fa-window-close" aria-hidden="true"></i></a></span></li>')
                },
            })
        }
    })
}

function deleteLastLiList(formId)
{
    $("#list_" + formId + " a").click( function (e) {
        /**
         * Disable normal form event
         * && get id and value
         */
        e.preventDefault()
        let $this = $(this)
        let value = $('#value_' + formId).val()
        let listId = "#list_" + formId
        console.log($this)
    })
}


