<?php


namespace reb_livestream_classes\Boot;


use reb_livestream_classes\Immolive\ImmoliveEmails;

class ImmoliveSubscription {

	use ImmoliveEmails;

	public function __construct() {
		add_action( 'wp_ajax_immolive_subscription', [ $this, 'subscribe' ] );
		add_action( 'wp_ajax_immolive_is_subscribed', [ $this, 'is_subscribed' ] );
		add_action('publish_immolive', [$this, 'create_immolive_list']);
		add_action('save_post_immolive', [$this, 'create_reminder_campaign'], 20,2);
		add_action('acf/save_post', [$this, 'create_ics_datei'], 20 );
	}




	public function is_subscribed(){

		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp_rest' ) ) {
			wp_die( 'Spamschutz', 400 );
		}

		$subscribers = get_field('field_601451bb66bc3', $_POST['id']);
		$user = wp_get_current_user();
		foreach ( $subscribers as $subscriber ) {
			if($subscriber['user_email'] ==  $user->user_email){
				wp_die(true);
			}
		}
		wp_die(false);

	}






	public function subscribe() {

		if ( ! wp_verify_nonce( sanitize_text_field( $_POST['nonce'] ), 'wp_rest' ) ) {
			wp_die( 'Spamschutz', 400 );
		}

		$immolive_id = (int) $_POST['id'];

		if ( get_post_type( $immolive_id ) !== 'immolive' ) {
			wp_die( 'Datenfehler', 400 );
		}

		$user = wp_get_current_user();

		$this->add_subscriber_to_list($immolive_id, $user);

		$registrants = get_field( 'field_601451bb66bc3', $immolive_id );
		foreach ( $registrants as $registrant ) {
			if ( $registrant['user_email'] == $user->user_email ) {
				wp_die( 'Anemldung erfolgreich!' );
			}
		}

		$question = sanitize_text_field( $_POST['question'] );

		$added = add_row( 'field_601451bb66bc3', [
			'user_name'        => $user->display_name,
			'user_email'       => $user->user_email,
			'frage_ans_podium' => $question,
		], $immolive_id );




		if ( $added ) {

			if(!empty($question)){
				wp_insert_comment([
					'comment_author' => $user->display_name,
					'comment_author_email' => $user->user_email,
					'comment_content' => $question,
					'comment_post_ID' => $immolive_id,
					'user_id'   => $user->ID
				]);
			}


			$this->send_subscription_email( $user->display_name, $user->user_email, $immolive_id );
			wp_die( 'Anmeldung erfolgreich!' );

		} else {
			wp_die( 'Datenfehler', 400 );
		}

	}

	public function create_ics_datei( $post_id ) {

		if(get_post_type($post_id) !== 'immolive') return;

		$exists = get_field('field_6143982f5f5f2', $post_id);
		if(!empty($exists)) return;


		$starts = new \Carbon\Carbon( get_field( 'field_5ed527e9c2279', $post_id, false ), 'Europe/Vienna' );
		$starts->setTimezone( 'UTC' );

		$start = $starts->format( 'Ymd\THis\Z' );
		$end   = $starts->addHour()->format( 'Ymd\THis' );


		$ics = new \reb_livestream_classes\ICS( [
			'location'    => 'online',
			'description' => get_the_excerpt( $post_id ),
			'dtstart'     => $start,
			'dtend'       => $end,
			'summary'     => get_the_title( $post_id ),
			'url'         => get_the_permalink( $post_id ),
			'vtimezone'   => 'Europe/Vienna',
		] );

		$template_dir = reb_livestream_theme_DIR . '/tmp/';
		$filename     = $template_dir . $post_id . '.ics';

		$file = fopen( $filename, 'w' );
		fwrite( $file, $ics->to_string() );
		fclose( $file );

		require( ABSPATH . 'wp-load.php' );
		$wordpress_upload_dir = wp_upload_dir();
		$new_file_path        = $wordpress_upload_dir['path'] . '/' . $post_id . '.ics';

		rename( $filename, $new_file_path );

		$upload_id = wp_insert_attachment( [
			'guid'           => $new_file_path,
			'post_mime_type' => 'text/calendar',
			'post_title'     => preg_replace( '/\.[^.]+$/', '', $post_id . '.ics' ),
			'post_content'   => '',
			'post_status'    => 'inherit',
		], $new_file_path );

		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		$metadata = wp_generate_attachment_metadata( $upload_id, $new_file_path );
		wp_update_attachment_metadata( $upload_id,  $metadata );

		$saved = update_field('field_6143982f5f5f2', $upload_id, $post_id);

	}

}