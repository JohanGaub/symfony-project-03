$(document).on('change', '#app_bundle_category_type', function () {
    let $form   = $(this).closest('form')
    let target = '#' + $(this).attr('id').replace('category_type', 'category')
    let data   = {}
    data[$(this).attr('name')] = $(this).val()

    $.post($form.attr('action'), data).then(function (data) {
        let $input = $(data).find(target)
        $(target).replaceWith($input)
    })
})