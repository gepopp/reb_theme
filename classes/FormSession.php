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
            <div class="text-warning p-5 text-white flex space-x-3 items-center">
                <div>
                    <div class="rounded-full bg-warning bg-opacity-25 w-10 h-10 flex items-center justify-center">
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
			'nonce'                 => __( "Wir konnten nicht verifizieren dass, das Formular von einem Menschen geschickt wurde. Bitte laden Sie die Seite neu und versuchen Sie es noch einmal.", 'ir21' ),
			'agb'                   => __( "Bitte akzeptieren Sie die AGB.", 'ir21' ),
			'register_error'        => __( "Wir konnten Ihre Daten nicht speichern, bitte versuchen Sie es später noch einmal.", 'ir21' ),
			'register_sent_success' => sprintf( __( 'Wir haben Ihnen ein E-Mail mit einem Link zum bestätigen Ihrer E-Mail Adresse, bitte überprüfen Sie Ihre Posteingang. <a href="%s" class="font-medium underline">Link nocheinmal senden</a>', 'ir21' ), get_field( 'field_601ed5b0226a0', 'option' ) ),
			'not_sent'              => sprintf( __( '<p>Wir konnten Ihnen kein E-Mail senden, bitte versuchen Sie es später erneut oder wenden Sie sich an den <a href="%s" class="underline">Administrator</a>.</p><p>Bitte beachten Sie, dass dieses E-Mail nur alle 5 Minuten versendet werden können.</p>', 'if21' ), get_option( 'admin_email' ) ),
			'not_activated'         => sprintf( __( 'Sie haben Ihre E-Mail Adresse noch nicht bestätigt, bitte überprüfen Sie Ihr E-Mail Postfach. Sollten Sie kein E-Mail erhalten haben können Sie <a href="%s" class="font-medium underline">hier ein neues anfordern.</a>', 'ir21' ), get_field( 'field_601ed5b0226a0', 'option' ) ),
			'login_credentials'     => __( 'Wir konnten Sie mit dieser Kombination aus E-Mail und Passwort nicht einloggen. Bitte versuchen Sie es erneut.', 'ir21' ),
			'user_exists'           => __( 'Diese E-Mail-Adresse ist bereits registriert.', 'ir21' ),
			'password_length'       => __( 'Ihr Passwort muss mindestens 8 Zeichen lang sein.', 'ir21' ),
			'profile_updated'       => __( 'Ihre Profildaten wurden erfolgreich aktualisiert.', 'ir21' ),
			'token_expired'         => sprintf( __( 'Dieser Link ist nicht mehr gültig, Sie können über die <a href="%s" class="font-semibold underline ">Passwort vergessen</a> funktion ein neues E-Mail anfordern', 'ir21' ), get_field( 'field_601e59c9336d7', 'option' ) ),
			'account_acitvated'     => __( 'Ihr Account ist nun aktiviert, Sie können sich jetzt einloggen!', 'ir21' ),
			'email_not_valid'       => __( 'Bitte geben Sie eine Valide Email Adresse ein.', 'ir21' ),
			'email_not_found'       => __( 'Wir konnten zu dieser Adresse keinen Eintrag finden, bitte versuchen Sie es noch einmal.', 'ir21' ),
			'reset_success'         => __( 'Wir haben Ihnen ein E-Mail mit einem Link zum zurücksetzen Ihres Passwortes gesendet, bitte überprüfen Sie Ihre Posteingang', 'ir21' ),
			'password_changed'      => __( 'Ihr neues Passwort wurde gespeichert, Sie können sich jetzt einloggen.', 'ir21' ),
			'profile_image_saved'   => __( 'Ihr neues Profilbild wurde gespeichert' ),
			'profile_image_size'    => __( 'Hier sind maximal 2 MB erlaubt.' ),
			'profile_image_mime'    => __( 'Hier sind nur jpg und png Dateien erlaubt.' ),
            'default'               => __('Ein Fehler trat auf, bitte versuchen Sie es später erneut.'),
            'zur_person_update'     => __('Vielen Dank, Ihr Vorschlag wurde erfolgreich eingereicht und wird nach Prüfung veröffentlicht.'),
            'uploaderror'           => _('Uploadfehler! Bitte laden Sie die Seite neu und versuchen Sie es noch einmal.'),

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