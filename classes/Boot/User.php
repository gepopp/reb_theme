<?php


namespace reb_livestream_classes\Boot;


use Carbon\Carbon;
use reb_livestream_classes\CampaignMonitor;
use function reb_livestream_theme\sentUserActivationToken;

class User {


	public function __construct() {

		add_role( 'registered', 'Registriert', [] );
		add_action( 'admin_init', [ $this, 'alter_capabilities' ] );
		add_filter( 'show_admin_bar', [ $this, 'hide_admin_bar' ] );
		add_action( 'wp_ajax_nopriv_user_exists', [ $this, 'ajax_user_exists' ] );
		add_action( 'wp_ajax_user_exists', [ $this, 'ajax_user_exists' ] );
		add_action( 'wp_ajax_update_reading_log', [ $this, 'update_reading_log' ] );
		add_action( 'wp_ajax_load_log', [ $this, 'load_reading_log' ] );
		add_filter( 'pre_get_avatar_data', [ $this, 'acf_profile_avatar' ], 10, 2 );
		add_action( 'admin_post_frontent_logout', [ $this, 'post_frontend_logout' ] );
		add_action( 'admin_post_nopriv_frontend_login', [ $this, 'frontend_login' ] );
		add_action( 'admin_post_nopriv_new_password', [ $this, 'new_password' ] );
		add_action( 'admin_post_nopriv_frontend_reset_password', [ $this, 'reset_password' ] );
		add_action( 'admin_post_nopriv_frontend_register', [ $this, 'frontend_register' ] );
		add_action( 'admin_post_nopriv_resend_activation', [ $this, 'resend_acitvation_link' ] );
	}

	public function resend_acitvation_link() {

		global $FormSession;

		$email = sanitize_email( $_POST['email'] );
		$user  = get_user_by( 'email', $email );

		if ( ! wp_verify_nonce( $_POST['resend_activation'], 'resend_activation' ) ) {
			$FormSession->addToErrorBag( 'resend_activation', 'nonce' )->redirect();
		}

		if ( ! $user ) {
			$FormSession->addToErrorBag( 'resend_activation', 'email_not_found' )->redirect();
		}

		if ( in_array( 'subscriber', $user->roles ) ) {
			$FormSession->set( 'token_success', 'account_acitvated' )->redirect( get_field( 'field_601bbffe28967', 'option' ) );
		}

		if ( $this->sentUserActivationToken( $user ) ) {
			$FormSession->set( 'resend_activation', 'register_sent_success' )->redirect();
		} else {
			$FormSession->addToErrorBag( 'resend_activation', 'not_sent' )->redirect();
		}

	}


	public function frontend_register() {


		global $FormSession;

		$gender    = sanitize_text_field( $_POST['register_gender'] );
		$firstname = sanitize_text_field( $_POST['first_name'] );
		$lastname  = sanitize_text_field( $_POST['last_name'] );
		$email     = sanitize_email( $_POST['register_email'] );
		$password  = sanitize_text_field( $_POST['password'] );

		$response = wp_remote_post( sprintf( 'https://www.google.com/recaptcha/api/siteverify?secret=%s&response=%s',
			get_field( 'field_6192780b4967b', 'option' ),
			sanitize_text_field( $_POST['grecaptcha'] )
		) );


		$captcha_response = json_decode( wp_remote_retrieve_body( $response ) );

		if ( ! isset( $captcha_response->success ) || ! $captcha_response->success ) {
			$FormSession->addToErrorBag( 'register_error', 'nonce' )->redirect();
			exit;
		}


		if ( ! wp_verify_nonce( $_POST['frontend_register'], 'frontend_register' ) ) {
			$FormSession->addToErrorBag( 'register_error', 'nonce' )->redirect();
			exit;
		}


		if ( sanitize_email( $email ) == '' ) {
			$FormSession->addToErrorBag( 'register_error', 'email_not_valid' )->redirect();
		}

		if ( get_user_by( 'email', $email ) ) {
			$FormSession->addToErrorBag( 'register_error', 'user_exists' )->redirect();
		}

		if ( sanitize_text_field( $_POST['agb'] ) != 'true' ) {
			$FormSession->addToErrorBag( 'register_error', 'agb' )->redirect();
		}

		if ( strlen( $password ) < 8 ) {
			$FormSession->addToErrorBag( 'register_error', 'password_length' )->redirect();
		}

		$user_id = wp_create_user( trim( $firstname . ' ' . $lastname . ' ' . uniqid() ), $password, $email );
		if ( is_wp_error( $user_id ) ) {
			$FormSession->addToErrorBag( 'register_error', 'register_error' )->redirect();
		}

		wp_update_user( [
			'ID'           => $user_id,
			'display_name' => trim( $firstname . ' ' . $lastname ),
			'first_name'   => $firstname,
			'last_name'    => $lastname,
		] );

		update_field( 'field_5fb6bc5f82e62', $gender, 'user_' . $user_id );

		$user = get_user_by( 'ID', $user_id );
		$user->add_role( 'registered' );
		$user->remove_role( 'subscriber' );

		$token = wp_generate_uuid4();

		$redirect = sanitize_text_field( $_POST['redirect'] ) ?? '';

		global $wpdb;
		$table = 'wp_user_activation_token';

		$insert = $wpdb->insert( $table, [
			'user_id'  => $user->ID,
			'email'    => $user->data->user_email,
			'token'    => $token,
			'redirect' => $redirect,
		],
			[ '%d', '%s', '%s', '%s' ] );


		$sent = ( new CampaignMonitor() )->transactional(
			'confirm_email_address',
			$user,
			[
				'link' => add_query_arg( [
					'token'    => $token,
					'redirect' => $redirect,
				], get_field( 'field_601bbffe28967', 'option' ) ),
			]
		);

		if ( $sent ) {
			$FormSession->set( 'register_sent_success', 'register_sent_success' )->redirect();
		} else {
			$FormSession->addToErrorBag( 'register_error', 'not_sent' )->redirect();
		}
	}


	function activate_user() {
		global $FormSession;

		$token = sanitize_text_field( $_GET['token'] );

		if ( $token == '' ) {
			return;
		}
		global $wpdb;

		$table = 'wp_user_activation_token';

		$email = $wpdb->get_var( 'SELECT email FROM ' . $table . ' WHERE token = "' . $token . '"' );


		$token_user = get_user_by( 'email', $email );

		if ( ! $token_user ) {
			$FormSession->addToErrorBag( 'login_errror', 'token_expired' );

			return;
		}

		if ( ! in_array( 'subscriber', $token_user->roles ) ) {

			$token_user->add_role( 'subscriber' );
			$token_user->remove_role( 'registered' );

			$cm = new CampaignMonitor();
			$cm->transactional(
				'registration_activated',
				$token_user,
				[
					'link' => get_field( 'field_601bbffe28967', 'option' ),
				]
			);
			$cm->updateUser( $token_user );
			$FormSession->set( 'token_success', 'account_acitvated' );

			return;
		}
		$FormSession->addToErrorBag( 'login_errror', 'token_expired' );

		return;
	}


	public function sentUserActivationToken( \WP_User $user ) {


		global $wpdb;
		$table = 'wp_user_activation_token';

		$last_token = $wpdb->get_var( sprintf( 'SELECT created_at FROM %s WHERE email = "%s" ORDER BY created_at DESC LIMIT 1', $table, $user->user_email ) );

		if ( $last_token && Carbon::now()->diffInMinutes( Carbon::parse( $last_token ) ) < 5 ) {
			return false;
		}

		$wpdb->delete( $table, [ 'email' => $user->data->user_email ] );
		$token = wp_generate_uuid4();

		$redirect = sanitize_text_field( $_POST['redirect'] ) ?? '';

		$wpdb->insert( $table, [
			'user_id'  => $user->ID,
			'email'    => $user->data->user_email,
			'token'    => $token,
			'redirect' => $redirect,
		],
			[ '%d', '%s', '%s', '%s' ] );

		return ( new CampaignMonitor() )->transactional(
			'confirm_email_address',
			$user,
			[
				'link' => add_query_arg( [
					'token'    => $token,
					'redirect' => $redirect,
				], get_field( 'field_601bbffe28967', 'option' ) ),
			]
		);
	}


	public function new_password() {

		global $FormSession;

		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['new_password'] ), 'new_password' ) ) {
			$FormSession->addToErrorBag( 'frontend_reset_password', 'nonce' )->redirect();
		}

		global $wpdb;
		$user = get_user_by( 'email', sanitize_email( $_POST['email'] ) );

		if ( ! $user ) {
			$FormSession->addToErrorBag( 'frontend_reset_password', 'register_error' )->redirect();
		}

		if ( ! in_array( 'subscriber', $user->roles ) && ! in_array('administrator', $user->roles) ) {
			$user->add_role( 'subscriber' );
			$user->remove_role( 'registered' );
		}

		wp_set_password( sanitize_text_field( $_POST['pw'] ), $user->ID );

		$FormSession->set( 'token_success', 'password_changed' )->redirect( get_field( 'field_601bbffe28967', 'option' ) );

	}


	public function reset_password() {

		global $FormSession;

		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['reset_password'] ), 'reset_password' ) ) {
			$FormSession->addToErrorBag( 'passwort_reset_error', 'nonce' )->redirect();
		}

		$user = get_user_by( 'email', sanitize_email( $_POST['email'] ) );

		if ( ! $user ) {
			$FormSession->addToErrorBag( 'passwort_reset_error', 'email_not_found' )->redirect();
		}

		global $wpdb;
		$table = 'wp_user_activation_token';

		$wpdb->delete( $table, [ 'email' => $user->data->user_email ] );

		$token = wp_generate_uuid4();

		$wpdb->insert( $table, [
			'user_id'    => $user->ID,
			'email'      => $user->data->user_email,
			'token'      => $token,
			'created_at' => \Carbon\Carbon::now()->format( 'd.m.Y H:i:s' ),
		],
			[ '%d', '%s', '%s', '%s' ] );

		$sent = ( new CampaignMonitor() )->transactional(
			'reset_password',
			$user,
			[
				'link'  => add_query_arg( [ 'token' => $token ], get_field( 'field_601e5b029887d', 'option' ) ),
				'email' => $user->user_email,
			] );

		if ( $sent ) {
			$FormSession->set( 'passwort_reset', 'reset_success' )->redirect();
		} else {
			$FormSession->addToErrorBag( 'passwort_reset_error', 'register_error' )->redirect();
		}

	}


	public function frontend_login() {

		global $FormSession;

		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['frontend_login'] ), 'frontend_login' ) ) {
			$FormSession->addToErrorBag( 'login_errror', 'nonce' )->redirect();
		}

		$user = get_user_by( 'email', sanitize_email( $_POST['email'] ) );
		if ( ! $user ) {
			$FormSession->addToErrorBag( 'login_errror', 'login_credentials' )->redirect();
		}

		$roles = $user->roles;

		if ( in_array( 'registered', $roles ) ) {
			$FormSession->addToErrorBag( 'login_errror', 'not_activated' )->redirect();
		}

		$user = wp_signon( [
			'user_login'    => sanitize_email( $_POST['email'] ),
			'user_password' => sanitize_text_field( $_POST['password'] ),
			'remember'      => isset( $_POST['remember'] ) ? (bool) $_POST['remember'] : false,
		], true );
		wp_set_current_user( $user );


		if ( is_wp_error( $user ) ) {
			$FormSession->addToErrorBag( 'login_errror', 'login_credentials' )->redirect();
		}

		if ( ! empty( $_POST['redirect'] ) ) {
			wp_safe_redirect( $_POST['redirect'] );
		} else {
			wp_safe_redirect( get_field( 'field_601bc4580a4fc', 'option' ) );
		}
	}


	public function post_frontend_logout() {
		wp_logout();
		wp_safe_redirect( home_url() );
	}


	public function alter_capabilities() {

		global $wp_roles; // global class wp-includes/capabilities.php
		$wp_roles->remove_cap( 'subscriber', 'read' );
		$wp_roles->remove_cap( 'registered', 'read' );
		$wp_roles->remove_cap( 'subscriber', 'edit_dashboard' );
		$wp_roles->remove_cap( 'registered', 'edit_dashboard' );

	}


	public function hide_admin_bar() {
		if ( ! current_user_can( 'administrator' ) ) {
			return false;
		}

		return true;
	}


	public function ajax_user_exists() {
		if ( get_user_by( 'email', sanitize_email( $_POST['email'] ) ) ) {
			wp_die( 'success' );
		} else {
			wp_die( 'nope', 400 );
		}
	}


	public function update_reading_log() {

		$user = sanitize_text_field( $_POST['user'] );
		$post = sanitize_text_field( $_POST['post'] );

		$depth = (int) sanitize_text_field( $_POST['depth'] ) > 100 ? 100 : sanitize_text_field( $_POST['depth'] );

		if ( $depth > 10 ) {

			global $wpdb;
			$exist = $wpdb->get_var( sprintf( 'SELECT id FROM wp_reading_log WHERE user_id = %d AND post_id = %d', $user, $post ) );

			if ( $exist == null ) {
				$wpdb->insert( 'wp_reading_log',
					[
						'user_id'      => $user,
						'post_id'      => $post,
						'scroll_depth' => $depth,
						'permalink'    => get_the_permalink( $post ),
						'created_at'   => Carbon::now()->format( 'Y-m-d H:i:s' ),
					], [ '%d', '%d', '%d', '%s', '%s' ] );
			} else {
				$wpdb->update( 'wp_reading_log',
					[ 'scroll_depth' => $depth ],
					[ 'id' => $exist ],
					[ '%d' ],
					[ '%d' ] );
			}
			wp_die( $depth );
		}
	}


	public function load_reading_log() {

		global $wpdb;

		$log = sanitize_text_field( $_POST['log'] );

		if ( $log == 'read' ) {
			$sql = sprintf( 'SELECT * FROM wp_reading_log WHERE user_id = %d AND scroll_depth = 100 ORDER BY created_at DESC LIMIT %d, 10', sanitize_text_field( $_POST['user_id'] ), sanitize_text_field( $_POST['offset'] ) );
		}

		if ( $log == 'not_read' ) {
			$sql = sprintf( 'SELECT * FROM wp_reading_log WHERE user_id = %d AND scroll_depth < 100 ORDER BY created_at DESC LIMIT %d, 10', sanitize_text_field( $_POST['user_id'] ), sanitize_text_field( $_POST['offset'] ) );
		}

		$logs = $wpdb->get_results( $sql );

		$return = [];
		foreach ( $logs as $d ) {

			$cat = get_the_category( $d->post_id );
			$cat = array_shift( $cat );

			$author = 'Von ' . get_the_author_meta( 'display_name', get_post_field( 'post_author', $d->post_id ) ) . ' am ' . get_the_time( 'd.m.Y', $d->post_id );

			$return[] = [
				'id'        => $d->id,
				'title'     => html_entity_decode( get_the_title( $d->post_id ) ),
				'permalink' => get_the_permalink( $d->post_id ),
				'cat'       => $cat->name ?? '',
				'author'    => $author,
				'time'      => ucfirst( Carbon::parse( $d->created_at )->diffForHumans() . ' zu ' . $d->scroll_depth ) . '%',
			];
		}

		wp_die( json_encode( $return ) );

	}


	public function acf_profile_avatar( $args, $id_or_email ) {

		if ( $id_or_email instanceof \WP_Comment ) {

			if ( ! empty( $id_or_email->user_id ) ) {

				$user = get_user_by( 'id', (int) $id_or_email->user_id );

			}

			// Get the file id
			$image = get_field( 'field_5ded37c474589', 'user_' . $user->ID ); // CHANGE TO YOUR FIELD NAME


			//wp_die(var_dump($image));

			// Bail if we don't have a local avatar
			if ( ! $image ) {
				return $args;
			}

			$image_id = $image['ID'];

			switch ( $args['size'] ) {
				case 24:
					$args['url'] = $image['sizes']['author_extra_small'] ?? $image['url'];
					break;
				case 48:
					$args['url'] = $image['sizes']['author_small'] ?? $image['url'];
					break;
				case 96:
					$args['url'] = $image['sizes']['author_large'] ?? $image['url'];
					break;
				default:
					$args['url'] = $image['url'];
			}

			return $args;
		}

		return $args;
	}

}