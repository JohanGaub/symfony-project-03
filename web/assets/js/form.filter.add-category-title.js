$(document).on('change', '#app_bundle_filter_type_categoryType', function () {
    let $form   = $(this).closest('form')
    let target  = '#' + $(this).attr('id').replace('Type', '')
    let data    = {}
    data[$(this).attr('name')] = $(this).val()

    $.post($form.attr('action'), data).then(function (data) {
        // get new <select>
        let $input = $(data).find(target)
        // replace current <select>
        $(target).replaceWith($input)
    })
})
