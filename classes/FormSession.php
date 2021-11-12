<?php

namespace reb_livestream_classes;

use Predis\Client;

class FormSession {


	public static $_instance = null;

	public $content = [];

	private $code;

	private $client;

	private $messages;


	public static function session() {
		if ( null === self::$_instance ) {

			self::$_instance = new self;
		}

		return self::$_instance;
	}


	protected function __construct() {
		$this->client = new Client();

		if ( isset( $_COOKIE['form_session'] ) ) {

			$this->code = $_COOKIE['form_session'];

			$this->content = unserialize( $this->client->get( $this->code ) );

			if ( ! is_array( $this->content ) ) {
				$this->content = [ 'formdata' => [], 'errorBag' => [] ];
			}

		} else {

			$this->code = wp_generate_uuid4();
			setcookie( 'form_session', $this->code, time() + 60 * 60 * 24, COOKIEPATH, COOKIE_DOMAIN );
			$this->content['formdata'] = [];
			$this->content['errorBag'] = [];
			$this->save();

		}

		$this->setupMessages();
		$this->content['formdata'] = empty( $_POST ) ? $this->content['formdata'] : $_POST;

	}

	public function redirect( $to = false, $done = false ) {
		$this->save();
		if ( ! $to ) {
			wp_safe_redirect( remove_query_arg( 'token', home_url( $_POST['_wp_http_referer'] ?? '' ) ) );
		} else {
			wp_safe_redirect( remove_query_arg( 'token', $to ) );
		}
		if ( $done ) {
			$this->done();
		}
		exit;
	}


	public function set( $index, $message ) {
		if ( array_key_exists( $message, $this->messages ) ) {
			$this->content[ $index ] = $this->messages[ $message ];
		} else {
			$this->content[ $index ] = $message;
		}

		$this->save();

		return $this;
	}


	public function addToErrorBag( $index, $message ) {

		if ( array_key_exists( $message, $this->messages ) ) {
			$this->content['errorBag'][ $index ][ $message ] = $this->messages[ $message ];
		} else {
			$this->content['errorBag'][ $index ][] = $message;
		}

		$this->save();

		return $this;
	}


	public function flashErrorBag( $index ) {

		if ( ! empty( $this->content['errorBag'][ $index ] ) ):
			?>
            <div class="text-warning py-5 text-white flex space-x-3 items-center">
                <div>
                    <div class="rounded-full bg-warning w-10 h-10 flex items-center justify-center">
                        <svg class="h-4 w-4 text-white animate-ping" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
                <ul class="text-warning text-sm">
					<?php foreach ( $this->content['errorBag'][ $index ] as $error ): ?>
                        <li><?php echo $error ?></li>
					<?php endforeach; ?>
                </ul>
            </div>
		<?php
		endif;
		$this->content['errorBag'] = [];
		$this->save();
	}

	public function flashSuccess( $index ) {
		if ( isset( $this->content[ $index ] ) ):
			?>
            <div class="text-success p-5 text-white flex space-x-3 items-center">
                <div>
                    <div class="rounded-full bg-success bg-opacity-25 w-10 h-10 flex items-center justify-center">
                        <svg class="h-8 w-8 text-white animate-ping" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-success text-sm">
					<?php echo $this->content[ $index ] ?>
                </p>
            </div>
		<?php
		endif;
		unset( $this->content[ $index ] );
		$this->save();
	}

	public function get( $index ) {
		if ( ! array_key_exists( $index, $this->content ) ) {
			return false;
		}

		$message = $this->content[ $index ];
		unset( $this->content[ $index ] );

		$this->save();

		return $message;
	}

	public function getFormData() {
		$data                      = $this->content['formdata'] ?? [];
		$this->content['formdata'] = [];

		return $data;
	}

	private function save() {
		$this->client->setex( $this->code, 60 * 60 * 24, serialize( $this->content ) );
		return $this;
	}

	public function __invoke( $index ) {
		return $this->content[ $index ] ?? false;
	}

	protected function setupMessages() {
		$this->messages = [
			'nonce'                 => __( "We could not verify that the data was sent by a human. Please reload this page and retry.", 'reb_domain' ),
			'agb'                   => __( "Please accept our terms.", 'reb_domain' ),
			'register_error'        => __( "We could not save your data, please retry.", 'reb_domain' ),
			'register_sent_success' => sprintf( __( 'We have sent you an email with an link to confirm your email address, please check your inbox. <a href="%s" class="font-medium underline">resend link</a>', 'reb_domain' ), get_field( 'field_601ed5b0226a0', 'option' ) ),
			'not_sent'              => sprintf( __( '<p>Sending you an email failed, please retry or contact our <a href="%s" class="underline">admin</a>.</p><p>Emails can only be send every five minutes.</p>', 'reb_domain' ), get_option( 'admin_email' ) ),
			'not_activated'         => sprintf( __( 'You did not confirm your email address yet, please check your inbox. If you havent received an email you can <a href="%s" class="font-medium underline">request an new one here.</a>', 'reb_domain' ), get_field( 'field_601ed5b0226a0', 'option' ) ),
			'login_credentials'     => __( 'This combination of email and password doesnt match any of our records.', 'reb_domain' ),
			'user_exists'           => __( 'Diese E-Mail-Adresse ist bereits registriert.', 'reb_domain' ),
			'password_length'       => __( 'Password must be at least 8 characters.', 'reb_domain' ),
			'profile_updated'       => __( 'Your profile was updated.', 'reb_domain' ),
			'token_expired'         => sprintf( __( 'This link is not valid anymore, You can request a new one via our <a href="%s" class="font-semibold underline ">password lost</a> function.', 'reb_domain' ), get_field( 'field_601e59c9336d7', 'option' ) ),
			'account_acitvated'     => __( 'Your account has been activated. You can login now.', 'reb_domain' ),
			'email_not_valid'       => __( 'Please enter a valid email address', 'reb_domain' ),
			'email_not_found'       => __( 'We where not able to find a record to the email you entered.', 'reb_domain' ),
			'reset_success'         => __( 'We sent you a link to reset your password, please check your inbox', 'reb_domain' ),
			'password_changed'      => __( 'Your new password is set, you can login now.', 'reb_domain' ),
			'profile_image_saved'   => __( 'Your profileimage was saved' ),
			'profile_image_size'    => __( 'Max 2 MB alowed.' ),
			'profile_image_mime'    => __( 'Only jpg or png files allowed.' ),
            'default'               => __('There was an error, please try again.'),
            'uploaderror'           => __('Uploaderror! Please refresh the page and try again.'),

		];

	}

	public function done() {
		$this->client->del( $this->code );
		unset( $_COOKIE[ $this->code ] );
	}

	protected function __clone() {
	}


	public function has($index){
	    return array_key_exists('register_sent_success', $this->content);
	}

    public function hasSuccess(){
        return array_key_exists('success', $this->content);
    }
}