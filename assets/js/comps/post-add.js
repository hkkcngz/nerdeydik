(function ($) {

    var el_form = $('#form-new-post'),
        el_form_submit = $('.submit', el_form);

    // Fires when the form is submitted.
    el_form.on('submit', function (e) {
        e.preventDefault();

        el_form_submit.attr('disabled', 'disabled');

        new_post();
    });

    // Ajax request.
    function new_post() {
        $.ajax({
            url: localized_new_post_form.admin_ajax_url,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'new_post', // Set action without prefix 'wp_ajax_'.
                form_data: el_form.serialize()
            },
            cache: false
        }).done(function (r) {
            if (r.post !== '' && r.preview_link !== '') {
                $('[name="ID"]', el_form).attr('value', r.post.ID);
                $('.preview-link', el_form)
                    .attr('href', r.preview_link)
                    .show();
                el_form_submit.attr('data-is-updated', 'true');
                el_form_submit.text(el_form_submit.data('is-update-text'));
            }

            el_form_submit.removeAttr('disabled');
        });
    }

    // Used to trigger/simulate post submission without user action.
    function trigger_new_post() {
        el_form.trigger('submit');
    }

    // Sets interval so the post the can be updated automatically provided that it was already created.
    setInterval(function () {
        if (el_form_submit.attr('data-is-updated') === 'false') {
            return false;
        }

        trigger_new_post();
    }, 5000); // Set to 5 seconds.

})(jQuery);