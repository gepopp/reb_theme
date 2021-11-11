<?php


namespace reb_livestream_classes\Boot;


class AlterEmailPin {

	public function __construct() {

		add_action('wp_ajax_send_email_pin', [$this, 'send_email_pin']);
		add_action('wp_ajax_validate_pin', [$this, 'validate_email_pin']);

	}

	public function validate_email_pin() {

		global $wpdb;
		$pin_row = $wpdb->get_row('SELECT * FROM wp_user_pending_email WHERE pin = ' . sanitize_text_field($_POST['pin']));

		if (!$pin_row || sanitize_email($_POST['email']) != $pin_row->new_email) {
			wp_die('Pin falsch', 400);
		}

		wp_update_user([
			'ID'         => $pin_row->user_id,
			'user_email' => $pin_row->new_email,
		]);

		wp_die('update erfolgreich');

	}




	public function send_email_pin() {

		$user = get_user_by('email', sanitize_email($_POST['old_email']));

		if (!$user) {
			wp_die("Wir konnten keinen Pin senden, laden Sie die Seite neu und versuchen sie es erneut.", 400);
		}

		global $wpdb;

		$wpdb->delete('wp_user_pending_email', ['user_id' => $user->ID]);

		$pin = rand(1001, 9999);

		$wpdb->insert('wp_user_pending_email',
			[
				'user_id'   => $user->ID,
				'new_email' => sanitize_email($_POST['email']),
				'pin'       => $pin,
			],
			['%d', '%s', '%d']);


		$result = wp_remote_post('https://api.createsend.com/api/v3.2/transactional/smartEmail/0ee71250-5880-473a-b721-bd741fa17f0d/send', [
			'headers' => [
				'authorization' => 'Basic ' . base64_encode('fab3e169a5a467b38347a38dbfaaad6d'),
			],
			'body'    => json_encode([
				'To'                  => $user->data->user_email,
				"Data"
				                      => [
					'fullname' => $user->data->display_name,
					'PIN'      => $pin,
				],
				"AddRecipientsToList" => false,
				"ConsentToTrack"      => "Yes",
			]),
		]);


		if (wp_remote_retrieve_response_code($result) < 200 || wp_remote_retrieve_response_code($result) > 299) {
			wp_die('Wir konnten keinen Pin senden.', 400);
		}
		wp_die('success');
	}

}