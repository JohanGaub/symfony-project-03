$(document).ready( function () {
    /**
     * Click for validate or closest
     */
    $('.link-action').click( function (e) {
        e.preventDefault()
        let $this       = $(this)
        let data        = $this.attr('data-info')
        let userId = $this.attr('data-id')
        let icon        = $this.children()

        $.ajax({
            type: 'POST',
            url: '/register_validation/' + userId,
            data : {
                'data': data,
            },
            dataType: 'json',
            timeout: 3000,
            down: function(datas){
                // change content of span who have current value

                if(datas === 'down'){
                    if(data === '1'){
                        icon.replaceWith('<i class="fa  fa-ban fa-2x" aria-hidden="true"></i>')
                    } else {
                        icon.replaceWith('<i class="fa fa-check-square fa-ban fa-2x" aria-hidden="true"></i>')
                    }
                }
            }
        })
    })
});




