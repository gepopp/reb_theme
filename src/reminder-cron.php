<?php
add_action("switch_theme", function () {
    wp_clear_scheduled_hook('send_reminder_daily');
}, 10, 2);


add_action("after_switch_theme", function () {
    if (!wp_next_scheduled('send_reminder_daily')) {
        wp_schedule_event(time(), 'daily', 'send_reminder_daily');
    }
});

add_action('send_reminder_daily', function (){
    global $wpdb;
    $reminders = $wpdb->get_results('select * from wp_user_read_later where date(remind_at) = CURDATE();');

    foreach ($reminders as $reminder) {


        $user = get_user_by('ID', $reminder->user_id);

        $cm = new \reb_livestream_theme\CampaignMonitor();

        $sent = $cm->transactional('reading_log_reminder',
            $user,
            [
                'title'   => get_the_title($reminder->post_id),
                'excerpt' => get_the_excerpt($reminder->post_id),
                'link'    => get_the_permalink($reminder->post_id),
            ]);

        $wpdb->delete('wp_user_read_later', ['id' => $reminder->id]);

    }

});

