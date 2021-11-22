<?php


namespace reb_livestream_classes\Boot;


use reb_livestream_classes\CampaignMonitor;

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

		$user = wp_get_current_user();

		if (!$user) {
			wp_die(_e('Error while sending pin, plrease retry', 'reb_domain'), 400);
		}

		global $wpdb;

		$wpdb->delete('wp_user_pending_email', ['user_id' => $user->ID]);

		$pin = rand(1001, 9999);

		$wpdb->insert('wp_user_pending_email',
			[
				'user_id'   => $user->ID,
				'new_email' => sanitize_email($_POST['email']),
				'PIN'       => $pin,
			],
			['%d', '%s', '%d']);


		$result = wp_remote_post(sprintf('https://api.createsend.com/api/v3.2/transactional/smartEmail/%s/send', get_field('field_619a658978459', 'option')), [
			'headers' => CampaignMonitor::get_authorization_header(),
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


		$cm = new CampaignMonitor();
		if(!$cm->isSuccess($result)){
			wp_die(_e('Error while sending pin, plrease retry', 'reb_domain'), 400);
		}else{
			wp_die('success');
		}

	}

}