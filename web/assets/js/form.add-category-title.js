$(document).on('change', '#app_bundle_category_type', function () {
    let $form   = $(this).closest('form')
    let target  = '#' + $(this).attr('id').replace('category_type', 'category')
    let data    = {}
    data[$(this).attr('name')] = $(this).val()
    console.log(data)

    $.post($form.attr('action'), data).then(function (data) {
        // get new <select>
        let $input = $(data).find(target)
        console.log(target)
        // replace current <select>
        $(target).replaceWith($input)
        console.log($input)
    })
})
