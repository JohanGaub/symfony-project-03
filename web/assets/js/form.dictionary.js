let formId = [
    'category_type',
    'technical_evolution_status',
    'technical_evolution_origin',
]

function formListening(formId)
{
    $("#dictionary_form_" + formId).submit( function (e) {
        e.preventDefault()
        let $form = $(this).closest('form')
        let data = {}
        data[$(this).attr('name')] = $(this).val()
        //dump($(this))

        $.post($form.attr('action'), data).then(function (data) {

        })
    })
}
formListening('category_type')