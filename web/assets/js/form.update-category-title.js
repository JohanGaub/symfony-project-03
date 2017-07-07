$(document).ready(function () {
    let selectTransformer = function (type) {
        let selectId    = 'app_bundle_' + type
        let $oldSelect  = $('#' + selectId)
        let $newSelect  = $('#hidden-select-inject-' + type)

        $oldSelect.replaceWith($newSelect.removeClass('hidden'))
        $newSelect.attr('id', selectId)
    }
    selectTransformer('category')
    selectTransformer('category_type')
})
