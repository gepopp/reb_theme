<?php


namespace reb_livestream_classes\Boot;


use reb_livestream_classes\FormSession;

class ZurPersonUpdate {


	protected FormSession $session;

	protected $person_id;

	protected $logo_dir;

	protected $description;
	protected $firstname;
	protected $lastname;
	protected $company;
	protected $position;
	protected $cv;

	protected $link;

	const LOGO_DIR = reb_livestream_theme_DIR . '/zur_person_bilder';

	public function __construct() {

		add_action( 'admin_post_save_zur_person_update', [ $this, 'sent_update_email' ] );

	}


	function sent_update_email() {


		global $FormSession;

		if ( ! is_user_logged_in()
		     || ! wp_verify_nonce( $_POST['zur_person_update_nonce'], 'zur_person_update' )
		     || get_the_permalink( sanitize_text_field( $_POST['zur_person_id'] ) ) != home_url( sanitize_text_field( $_POST['_wp_http_referer'] ) ) ) {

			$FormSession->addToErrorBag( 'global_error', 'nonce' )->redirect();
			exit;
		}

		$this->person_id = sanitize_text_field( $_POST['zur_person_id'] );


		if (  $_FILES['image']['size'] ) {

			$this->logo_dir = $this->handle_logo_upload( $_FILES['image'], [ 'image/jpeg', 'image/png', 'image/jpg' ] );
			if ( ! $this->logo_dir ) {
				$FormSession->addToErrorBag( 'uploaderror', 'uploaderror' )->redirect();
				exit;
			}
		}

		if (  $_FILES['lebenslauf']['size'] ) {

			$this->cv = $this->handle_logo_upload( $_FILES['lebenslauf'], [ 'application/pdf' ] );
			if ( ! $this->cv ) {
				$FormSession->addToErrorBag( 'uploaderror', 'uploaderror' )->redirect();
				exit;
			}
		}

		$this->firstname   = sanitize_text_field( $_POST['firstname'] );
		$this->lastname    = sanitize_text_field( $_POST['lastname'] );
		$this->company     = sanitize_text_field( $_POST['company'] );
		$this->position    = sanitize_text_field( $_POST['position'] );
		$this->description = sanitize_text_field( $_POST['description'] );
		$this->companylink = sanitize_text_field( $_POST['companylink'] );

		if ( $this->notify_admin() ) {
			foreach ( glob(self::LOGO_DIR . '/*') as $item ) {
				if(!is_dir($item) && file_exists($item)) {
					unlink($item);
				}
			}
			$FormSession->set( 'success', 'Vielen Dank, Ihr Vorschlag wurde übermittelt und wird nach Prüfung veröffentlicht.' )->redirect();
			exit;
		} else {
			$FormSession->addToErrorBag( 'global_error', 'default' )->redirect();
			exit;
		}

	}


//	public function save_order() {
//
//		global $wpdb;
//		$saved = $wpdb->insert( 'wp_tl_company_orders',
//			[
//				'order_id'    => wp_generate_uuid4(),
//				'company_id'  => $this->person_id,
//				'user_id'     => get_current_user_id(),
//				'logo_path'   => $this->logo_dir,
//				'description' => $this->description,
//				'company_url' => $this->link,
//				'status'      => 'placed',
//			], [ '%s', '%d', '%d', '%s', '%s', '%s', '%s' ] );
//
//		if ( is_wp_error( $saved ) ) {
//			$this->session->Add( 'errors', 'Es trat ein Fehler beim Speichern der Daten auf. Bitte versuchen Sie es Später erneut.' );
//			$this->Back();
//		}
//
//		return $saved;
//
//	}


	public function handle_logo_upload( $file, $type ) {


		global $FormSession;


		if ( $file['error'] != UPLOAD_ERR_OK ) {
			$FormSession->addToErrorBag( 'uploaderror', 'uploaderror' )->redirect();
			exit;
		}

		if ( ! in_array( $file['type'], $type ) ) {
			$FormSession->addToErrorBag( 'uploaderror', 'profile_image_mime' )->redirect();
			exit;
		}

		if ( $file['size'] < 1 || $file['size'] > 2097152 ) {
			$FormSession->addToErrorBag( 'uploaderror', 'profile_image_size' )->redirect();
			exit;
		}


		if ( ! is_dir( self::LOGO_DIR ) ) {
			mkdir( self::LOGO_DIR );
		}

		$filename = self::LOGO_DIR . '/' . $file['name'];

		if ( move_uploaded_file( $file['tmp_name'], $filename ) ) {
			return $filename;
		} else {
			return false;
		}

	}


	public function notify_admin() {

		$user = wp_get_current_user();

		$content = '<p>Der Nutzer mit der ' . $user->display_name . ' ( ' . $user->user_email . ' ) hat zum Unternehmen ' . get_the_title( $this->person_id ) . ' eine Bestellung aufgegben.</p>';
		$content .= '<p>&nbsp;</p>';
		$content .= '<p>Zur Person: ' . get_edit_post_link( $this->person_id ) . '</p>';
		$content .= '<hr>';
		$content .= 'Vorname: ' . $this->firstname;
		$content .= '<p>&nbsp;</p>';
		$content .= 'Nachname: ' . $this->lastname;
		$content .= '<p>&nbsp;</p>';
		$content .= 'Untenrehmen: ' . $this->company;
		$content .= '<p>&nbsp;</p>';
		$content .= 'Position: ' . $this->position;
		$content .= '<p>&nbsp;</p>';
		$content .= 'Beschreibung: ' . $this->description;
		$content .= '<p>&nbsp;</p>';
		$content .= 'Link: ' . $this->companylink;


		return wp_mail( 'gerhard@poppgerhard.at', 'Neue Vorschlag zur Person', $content,
			[
				'Bcc: gerhard@poppgerhard.at',
				'Content-Type: text/html; charset=UTF-8'
			],
			[
				$this->logo_dir,
				$this->cv
			] );
	}

}