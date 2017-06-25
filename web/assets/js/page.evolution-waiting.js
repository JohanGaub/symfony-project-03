$(document).ready( function () {
    /**
     * Click for validate or closest
     */
    $('.link-action').click( function (e) {
        e.preventDefault()
        let $this       = $(this)
        let data        = $this.attr('data-info')
        let evolutionId = $this.parent().attr('data-index-number')

        $.ajax({
            type: 'POST',
            url: '/evolution-technique/en-attente/traitement/' + evolutionId,
            data : {
                'data': data
            },
            dataType: 'json',
            timeout: 3000,
            success: function(data){
                // change content of span who have current value
                if (data === 'true'){
                    $('#tr_element_' + evolutionId).css({
                        backgroundColor : '#bfd9b7',
                        color : '#FFF'
                    })
                } else if (data === 'false'){
                    $('#tr_element_' + evolutionId).css({
                        backgroundColor : '#e9bdb5',
                        color : '#FFF'
                    })
                }
            },
        })
    })
})
