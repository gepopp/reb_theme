<?php

namespace reb_livestream_classes\Boot;

class ImmoLiveIcs {


	public function __construct() {

		add_action('acf/save_post', [$this, 'create_ics_datei'], 20 );

	}

	public function create_ics_datei( $post_id ) {

		if(get_post_type($post_id) !== 'livestream') return;

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