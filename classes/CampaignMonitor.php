<?php

namespace reb_livestream_classes;

class CampaignMonitor {

	protected $api_key = 'fab3e169a5a467b38347a38dbfaaad6d';

	protected $status;

	protected $transactionalIds = [
		'confirm_email_address'  => '7b1481ba-8715-48a0-8b4f-d17127675e23',
		'registration_activated' => '7303e85b-72c8-4acf-88ff-4e117ddb0fa9',
		'reset_password'         => '0fd34ccb-86f1-4aa5-99e0-43c64ddc6379',
		'reading_log_reminder'   => '83eeff44-68bc-4c85-a261-515cd60b3a09',
		'immolive_ical'          => 'f681cc3f-299d-447c-8444-4b7fbec46082',
	];


	public function transactional( $template, $user, $args = [], $attachment = false ) {


		$url = sprintf( 'https://api.createsend.com/api/v3.2/transactional/smartEmail/%s/send', $this->transactionalIds[ $template ] );

		$userdata = [
			'gender'    => get_field( 'field_5fb6bc5f82e62', 'user_' . $user->ID ),
			'firstname' => get_user_meta( $user->ID, 'first_name' ),
			'lastname'  => get_user_meta( $user->ID, 'first_name' ),
			'fullname'  => $user->data->display_name,
		];

		$result = wp_remote_post( $url, [
			'headers' => self::get_authorization_header(),
			'body'    => json_encode( [
				'To'                  => $user->data->user_email,
				"Data"
				                      => array_merge( $userdata, $args ),
				"AddRecipientsToList" => true,
				"ConsentToTrack"      => "Yes",
			] ),
		] );

		return $this->isSuccess( $result );
	}


	public function updateUser( $user ) {

		$result = wp_remote_post( 'https://api.createsend.com/api/v3.2/subscribers/2bf433e2872f0f1fb917ca1c98ff301e.json?email=' . $user->data->user_email, [
			'headers' => self::get_authorization_header(),
			'body'    => json_encode( [
				"EmailAddress"                           => $user->data->user_email,
				"Name"                                   => $user->data->display_name,
				"CustomFields"                           => [
					[
						"Key"   => 'anrede',
						"Value" => get_field( 'field_5fb6bc5f82e62', 'user_' . $user->ID ) == 'Herr' ? 'Sehr geehrter Herr' : 'Sehr geehrte Frau',
					],
				],
				"Resubscribe"                            => true,
				"RestartSubscriptionBasedAutoresponders" => true,
				"ConsentToTrack"                         => "Yes",
			] ),
		] );

		return $this->isSuccess( $result );
	}


	function isSuccess( $result ) : bool {

		$status = wp_remote_retrieve_response_code( $result );
		if ( $status < 300 && $status > 199 ) {
			return true;
		} else {
			wp_mail( 'gerhard@poppgerhard.at', 'CM Fehler', print_r( wp_remote_retrieve_body( $result ), true ) );
			return false;
		}
	}

	public static function get_authorization_header(){
		return [
			'authorization' => 'Basic ' . base64_encode( 'fab3e169a5a467b38347a38dbfaaad6d' )
		];
	}

}