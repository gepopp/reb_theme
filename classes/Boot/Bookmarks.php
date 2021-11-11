<?php


namespace reb_livestream_classes\Boot;

use Carbon\Carbon;

class Bookmarks {


	public function __construct() {


		add_action('wp_ajax_update_reminder_date', [$this, 'update_reminder_date'] );
		add_action('wp_ajax_set_reading_reminder', [$this, 'set_reading_reminder']);
		add_action('wp_ajax_set_user_bookmark', [$this, 'set_bookmark']);
		add_action('wp_ajax_remove_user_bookmark', [$this, 'delete_bookmard']);

	}

	public function delete_bookmard() {

		$bookmark_id = sanitize_text_field($_POST['id']);
		$user = wp_get_current_user();

		global $wpdb;
		$bookmark_user_id = $wpdb->get_var(sprintf('SELECT user_id FROM wp_user_bookmarks WHERE id = %d', $bookmark_id));
		if ($user->ID == $bookmark_user_id) {
			$del = $wpdb->delete('wp_user_bookmarks', ['id' => $bookmark_id]);
		}

		if (!is_wp_error($del)) {
			wp_die('');
		} else {
			wp_die('', 400);
		}

	}


	public function set_bookmark()
	{

		$post = sanitize_text_field($_POST['id']);
		$user = wp_get_current_user();

		global $wpdb;

		$wpdb->show_errors = false;

		$insert = $wpdb->insert('wp_user_bookmarks', [
			'user_id'   => $user->ID,
			'post_id'   => $post,
			'permalink' => get_the_permalink($post),
		], ['%d', '%d', '%s']);

		if (!$insert) {
			wp_die('', 401);
		} else {
			wp_die('');

		}

	}





	public function set_reading_reminder()
	{

		$post = sanitize_text_field($_POST['id']);
		$user = wp_get_current_user();

		global $wpdb;

		$wpdb->show_errors = false;

		$insert = $wpdb->insert('wp_user_read_later', [
			'user_id'   => $user->ID,
			'post_id'   => $post,
			'permalink' => get_the_permalink($post),
			'remind_at' => Carbon::now()->addDays(4)->format('Y-m-d H:i:s'),
		], ['%d', '%d', '%s', '%s']);

		if (!$insert) {
			wp_die('', 401);
		} else {
			wp_die('');

		}
	}





	public function update_reminder_date() {

		global $wpdb;

		if (get_current_user_id() == $wpdb->get_var(sprintf('SELECT user_id FROM wp_user_read_later WHERE id= %s', $_POST['id']))) {
			$update = $wpdb->update('wp_user_read_later', ['remind_at' => Carbon::now()->addDays($_POST['days'] + 1)->format('Y-m-d H:i:s')], ['id' => $_POST['id']]);
		}

		if ($update ?? false) {

			$log = $wpdb->get_row(sprintf('SELECT * FROM wp_user_read_later WHERE id = %s', $_POST['id']));

			\Carbon\Carbon::setLocale('de');
			wp_die(json_encode([
				'remind_at' => $log->remind_at,
				'time'      => ucfirst(\Carbon\Carbon::parse($log->remind_at)->diffForHumans()),
			]));

		} else {
			wp_die(false, 400);
		}


	}
}