jQuery.noConflict($);
/* Ajax functions */
jQuery(document).ready(function($) {
    //onclick
    $("#loadMore-single").on('click', function(e) {
        //init
        var that = $(this);
        var page = $(this).data('page');
        var newPage = page + 1;
        var ajaxurl = that.data('url');
        //ajax call
        $.ajax({
            url: ajaxurl,
            type: 'post',
            data: {
                page: page,
                action: 'ajax_script_load_more'
            },
            error: function(response) {
                console.log(response);
            },
            beforeSend: function (xhr) {
                // you can also add your own preloader here
                // you see, the AJAX call is in process, we shouldn't run it again until complete
                $('#loadmore-single').text('Yükleniyoor.');
            },
            success: function(response) {
                //check
                if (response == 0) {
                    $('#ajax-content').append('<div class="hc_loadmore"><p>Son satıra ulaştın! Daha başka yazı yok.</p></div>');
                    $('#loadMore-single').hide();
                } else {
                    that.data('page', newPage);
                    $('#ajax-content').append(response);
                    $('#loadmore-single').text('Yüklendi.');
                }
            }
        });
    });
});