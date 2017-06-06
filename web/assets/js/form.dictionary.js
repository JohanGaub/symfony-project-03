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
        let value = $('#value').val()

        if (value === ''){
            alert('Votre valeur ne peut être vide !')
        } else {
            $.ajax({
                type: $this.attr('method'),
                url: $this.attr('action'),
                dataType: $this.serialize(),
                timeout: 3000,
                success: function(){
                    $("list-" + formId).append('<li class="list-group-item">' + value + '</li>')
                },
            });
        }
    })
}

for (let id of formId){
    formListening(id)
}
