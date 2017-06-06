$( document ).ready(function() {
    for (let id of formId) {
        formListening(id)
    }
})

let formId = [
    'category_type',
    'technical_evolution_status',
    'technical_evolution_origin',
]

function formListening(formId)
{
    $("#dictionary_form_" + formId).submit( function (e) {
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
                success: function(){
                    $("#list_" + formId).append('<li class="list-group-item">' + value + '</li>')
                },
            })
        }
    })
}

