jQuery(document).ready(function ($) {

    $('#vimeo_thumbnail').on('click', function (e) {
        e.preventDefault();



        $.post(ajaxurl, {
           action: 'refresh_vimeo_image',
           post: $('#vimeo_thumbnail').data('post')
        }).then(function (){
            window.location.reload();
        });
    });
});
