<?php


namespace reb_livestream_classes\Boot;


use reb_livestream_classes\Immolive\ImmoliveEmails;

class ImmoliveSubscription {

	use ImmoliveEmails;

	public function __construct() {

		add_action( 'wp_ajax_immolive_subscription', [ $this, 'subscribe_to_livestream' ] );
		add_action( 'wp_ajax_immolive_subscription_email', [ $this, 'immolive_subscription_email' ] );
		add_action( 'publish_livestream', [ $this, 'create_immolive_list' ] );
		add_action( 'save_post_livestream', [ $this, 'create_reminder_campaign' ], 20, 2 );

	}

	public function immolive_subscription_email() {


		$verify        = $this->verify();
		$user          = $verify[0];
		$livestream_id = $verify[1];

		$this->add_subscriber_to_list( $livestream_id, $user );
		return $this->send_subscription_email($user->user_email, $livestream_id);


	}


	public function subscribe_to_livestream() {

		$verify        = $this->verify();
		$user          = $verify[0];
		$livestream_id = $verify[1];

		$registrants = get_field( 'field_601451bb66bc3', $livestream_id );
		foreach ( $registrants as $registrant ) {
			if ( $registrant['user_email'] == $user->user_email ) {
				wp_die( 'success' );
			}
		}

		$added = add_row( 'field_601451bb66bc3', [
			'user_name'  => $user->display_name,
			'user_email' => $user->user_email,
		], $livestream_id );

		if ( $added ) {
			wp_die( 'success' );
		} else {
			wp_die( 'error', 400 );
		}


	}

	public function verify() {

		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp_rest' ) ) {
			wp_die( __( 'Spam protection error, try again', 'reb_domain' ), 400 );
		}

		$livestream_id = (int) $_POST['id'];

		if ( get_post_type( $livestream_id ) !== 'livestream' ) {
			wp_die( __( 'Spam protection error, try again', 'reb_domain' ), 400 );
		}

		return [ wp_get_current_user(), $livestream_id ];

	}
}