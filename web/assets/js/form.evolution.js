$(document).on('change', '#app_bundle_technicalEvolution_category_type', function () {
    let $field = $(this)
    let $typeField = $('#app_bundle_technicalEvolution_category_type')
    let $form = $field.closest('form')
    let target = '#' + $field.attr('id').replace('category_type', 'category')
    // Datas to send at ajax
    let data = {}
    data[$typeField.attr('name')] = $typeField.val()
    data[$field.attr('name')] = $field.val()
    // Send ajax data
    $.post($form.attr('action'), data).then(function (data) {
        // get new <select>
        let $input = $(data).find(target)
        console.log(target)
        console.log($input)
        // replace current <select>
        $(target).replaceWith($input)
    })
})