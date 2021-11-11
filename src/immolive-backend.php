<?php
add_filter('post_row_actions', function ($actions, $post) {

    if (get_post_type($post) != 'immolive') return $actions;

    if (!get_field('field_601451bb66bc3', $post->ID)) return $actions;


    ob_start();
    ?>
    <a href="#" id="immolive_export_<?php echo $post->ID ?>" data-id="<?php echo $post->ID ?>">csv Anmelder Export</a>
    <script>
        jQuery(document).ready(function ($) {
            $('#immolive_export_<?php echo $post->ID ?>').on('click', function (e) {
                $.post(ajaxurl, {action: 'export_il_subscriber', id: $(this).data('id')})
                    .done(function (data) {

                        /*
                         * Make CSV downloadable
                         */
                        var downloadLink = document.createElement("a");
                        var fileData = ['\ufeff' + data];

                        var blobObject = new Blob(fileData, {
                            type: "text/csv;charset=utf-8;"
                        });

                        var url = URL.createObjectURL(blobObject);
                        downloadLink.href = url;
                        downloadLink.download = "<?php echo $post->ID ?>.csv";

                        /*
                         * Actually download CSV
                         */
                        document.body.appendChild(downloadLink);
                        downloadLink.click();
                        document.body.removeChild(downloadLink);

                    });
            });
        });
    </script>
    <?php

    $actions['test'] = ob_get_clean();

    return $actions;

}, 10, 2);


add_action('wp_ajax_export_il_subscriber', function () {


    $immolive_id = $_POST['id'];

    $subs = get_field('field_601451bb66bc3', $immolive_id);


    $fp = fopen('php://output', 'w');
    fputcsv($fp, array_keys(reset($subs)));

    foreach ($subs as $values):
        fputcsv($fp, $values);
    endforeach;

    fclose($fp);

});
