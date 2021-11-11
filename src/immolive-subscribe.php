<?php

namespace reb_livestream_theme;

use reb_livestream_classes\ZoomAPIWrapper;

add_action('admin_post_subscribe_immolive', function () {

    $wrapper = new ZoomAPIWrapper();


    if (!wp_verify_nonce($_POST['subscribe_immolive'], 'subscribe_immolive')) {
        wp_redirect(home_url());
    }

    if (sanitize_text_field($_POST['confirm']) !== 'on'
        || get_post_type($_POST['immolive_id']) != 'immolive'
    ) {
        wp_redirect(get_field('field_601e5f56775db', 'option'));
    }

    $user = wp_get_current_user();
    $immolive_id = $_POST['immolive_id'];

    $registrants = get_field('field_601451bb66bc3', $immolive_id);

    foreach ($registrants as $registrant) {
        if ($registrant['user_email'] == $user->user_email) {
            wp_safe_redirect(get_field('field_601e5f56775db', 'option'));
        }
    }

    $response = $wrapper->doRequest('POST', '/webinars/' . get_field('field_60127a6c90f6b', $immolive_id) . '/registrants', ['page_size' => 300], [], [
        'email'      => $user->user_email,
        'first_name' => $user->first_name,
        'last_name'  => $user->last_name,
        'comments'   => sanitize_text_field($_POST['question']),
    ]);

    add_row('field_601451bb66bc3', [
        'user_name'            => $user->first_name . ' ' . $user->last_name,
        'user_email'           => $user->user_email,
        'hat_dsg_bestatigt'    => 1,
        'frage_ans_podium'     => sanitize_text_field($_POST['question']),
        'zoom_registrant_id'   => $response['registrant_id'],
        'zoom_teilnehmer_link' => $response['join_url'],
        'referer'              => sanitize_text_field($_POST['referer']),
    ], $immolive_id);

    wp_safe_redirect(get_field('field_601e5f56775db', 'option'));

});