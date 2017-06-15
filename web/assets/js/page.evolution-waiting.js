$(document).ready( function () {
    /**
     * Click for validate
     */
    $(".validate-action").click( function (e) {
        /**
         * Disable normal form event
         * && get id from li for update by id
         */
        e.preventDefault()
        let id = $(this).attr('id').replace('validate_action_', '');

        $.ajax({
            type: 'POST',
            url: '/evolution-technique/en-attente/traitement/' + id,
            data : {
                'data': true
            },
            dataType: 'json',
            timeout: 3000,
            success: function(){
                // change content of span who have current value
                $('#tr_element_' + id).css({
                    backgroundColor : '#5CB85C',
                    color : '#FFF'
                })
            },
        })
    })

    $(".delete-action").click( function (e) {
        /**
         * Disable normal form event
         * && get id from li for update by id
         */
        e.preventDefault()
        let id = $(this).attr('id').replace('delete_action_', '');

        $.ajax({
            type: 'POST',
            url: '/evolution-technique/en-attente/traitement/' + id,
            data : {
                'data': false
            },
            dataType: 'json',
            timeout: 3000,
            success: function(){
                // change content of span who have current value
                $('#tr_element_' + id).css({
                    backgroundColor : '#D9534F',
                    color : '#FFF'
                })
            },
        })
    })
})
