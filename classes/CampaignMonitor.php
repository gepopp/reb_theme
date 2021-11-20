<?php

namespace reb_livestream_classes;

class CampaignMonitor {

	protected $api_key;

	protected $status;

	protected $transactionalIds = [];


	public function __construct() {

		$this->api_key = get_field( 'field_619276110f7be', 'option' );

		$this->transactionalIds = [
			'confirm_email_address'  => get_field( 'field_6192743aaa09e', 'option' ),
			'registration_activated' => get_field( 'field_61927455aa09f', 'option' ),
			'reset_password'         => get_field( 'field_61927c463a0a4', 'option' ),
			'immolive_ical'          => get_field( 'field_61927467aa0a0', 'option' ),
		];


	}

	public function transactional( $template, $user, $args = [], $attachment = false ) {


		if ( empty( $this->transactionalIds[ $template ] ) ) {

			$fallback = 'fallback_mail_' . $template;

			return $this->$fallback( $user, $args, $attachment );

		} else {
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


	function isSuccess( $result ): bool {

		$status = wp_remote_retrieve_response_code( $result );
		if ( $status < 300 && $status > 199 ) {
			return true;
		} else {
			wp_mail( 'gerhard@poppgerhard.at', 'CM Fehler', print_r( wp_remote_retrieve_body( $result ), true ) );

			return false;
		}
	}


	public static function get_authorization_header($xml = false) {

		if(!$xml){
			return [
				'authorization' => 'Basic ' . base64_encode( get_field('field_619276110f7be', 'option') ),
			];
		}

		return [
			'authorization' => 'Basic ' . base64_encode( get_field('field_619276110f7be', 'option') ),
			'mime-type' => 'text/xml'
		];
	}


	function fallback_mail_confirm_email_address( $user, $args, $attachment ) {

		$home = home_url();
		$link = $args['link'];

		$content = <<<EOM
Guten Tag,
vielen Dank für Ihre Registrierung auf $home.
Bitte aktivieren Sie Ihren Account über diesen Link: $link

Vielen Dank.

Mit freundlichen Grüßen
Ihr Real Estate Brand Talk Team
 
EOM;

		return wp_mail( $user->data->user_email, 'Schliessen Sie Ihre Registrierung ab', $content );
	}


	function fallback_mail_registration_activated( $user, $args, $attachment ) {

		$home = home_url();


		$content = <<<EOM
Guten Tag,
vielen Dank, Ihre Registrierung auf $home ist nun abgeschlossen.
Sie können sich jetzt einloggen.


Mit freundlichen Grüßen
Ihr Real Estate Brand Talk Team
 
EOM;

		return wp_mail( $user->data->user_email, 'Herzllich willkommen.', $content );


	}


	function fallback_mail_reset_password( $user, $args, $attachment ) {
		$home = home_url();
		$link = $args['link'];

		$content = <<<EOM
Guten Tag,
vielen Dank, bitte folgen Sie diesem Link: $link
um Ihr Passwort auf $home zurückzusetzen.


Mit freundlichen Grüßen
Ihr Real Estate Brand Talk Team
 
EOM;

		return wp_mail( $user->data->user_email, 'Passwort zurücksetzten', $content );
	}

}