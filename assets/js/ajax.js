// My Infinity Scroll and Search Filter by HC

jQuery(function ($) {

    $(document).on("click", "#loadmore-home", function () {
        loadArticle("toggle");
    });

    $(window).scroll(function () {
        if ($(window).scrollTop() == $(document).height() - $(window).height()) {
            loadArticle("toggle");
        }
    });

    function loadArticle(e) {

        $.ajax({
            url:  hc_loadmore_params.ajaxurl, // AJAX handler
            data : {
				'action': 'loadmore', // the parameter for admin-ajax.php
				'query': hc_loadmore_params.posts, // loop parameters passed by wp_localize_script()
				'page' : hc_loadmore_params.current_page // current page
			},
            type: 'POST',
            beforeSend: function (xhr) {
                // you can also add your own preloader here
                // you see, the AJAX call is in process, we shouldn't run it again until complete
                $('#loadmore-home').text('Yükleniyoor.');
            },
            success: function (data) {
                if (data) {
                    $('.posts').find('article:last-of-type').after(
                    data); // where to insert posts
                    $('#loadmore-home').text('Yüklendi.');
                    // the ajax is completed, now we can run it again
                    hc_loadmore_params.current_page++;
                } else {
                    $('#loadmore-home').text('Daha başka yazı yok.');
                }
            }
        });
    }

    // Filter by HC
    $('#hc_filters').change(function () {
        filterArticle();
    });

    function filterArticle(e) {

        $.ajax({
            url : hc_loadmore_params.ajaxurl,
            data : $('#hc_filters').serialize(), // form data
            dataType : 'json', // this data type allows us to receive objects from the server
            type : 'POST',
            beforeSend : function(xhr){
                $('#hc_filters').find('button').text('Filtreleniyoor...');
            },
            success : function( data ){

                // when filter applied:
                // set the current page to 1
                hc_loadmore_params.current_page = 1;

                // set the new query parameters
                hc_loadmore_params.posts = data.posts;

                // set the new max page parameter
                hc_loadmore_params.max_page = data.max_page;

                // change the button label back
                $('#hc_filters').find('button').text('Filtre Uygula');

                // insert the posts to the container
                $('.posts').html(data.content);

                // hide load more button, if there are not enough posts for the second page
                if ( data.max_page < 2 ) {
                    $('#loadmore-home').hide();
                } else {
                    $('#loadmore-home').show();
                }
            }
        });

        // do not submit the form
        return false;

    } // end filter func.


});