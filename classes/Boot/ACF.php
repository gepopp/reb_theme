<?php


namespace reb_livestream_classes\Boot;


use Carbon\Carbon;
use Carbon\CarbonInterval;
use reb_livestream_classes\CampaignMonitor;

class ACF {

	public function __construct() {

		add_action( 'acf/init', [ $this, 'ir_add_options_pages' ] );
		add_filter( 'acf/update_value/key=field_5a3ce915590ae', [ $this, 'update_duration' ], 10, 4 );
		add_filter( 'acf/update_value/key=field_5ed527e9c2279', [ $this, 'save_immolive_termin' ], 10, 4 );
	//	add_filter( 'acf/load_value/key=field_5ed527e9c2279', [ $this, 'load_immolive_termin' ], 10, 4 );
		add_filter('acf/load_field/key=field_616199812fbf4', [$this, 'set_immolive_actions_content']);
		add_filter('acf/load_field/key=field_61927472aa0a1', [$this, 'load_cm_templates']);

	}


    public function load_cm_templates( $field ){
  		$templates = wp_remote_get( sprintf( 'https://api.createsend.com/api/v3.2/clients/%s/templates.json', get_field( 'field_61938af4c1fcf', 'option' ) ), [
			'headers' => CampaignMonitor::get_authorization_header(),
		] );

        $body = json_decode(wp_remote_retrieve_body($templates));

        foreach($body as $template){
            $field['choices'][$template->TemplateID] = $template->Name;
        }

	    return $field;


    }

	public function set_immolive_actions_content($field){

        global $post;

		ob_start();
		?>
		<button id="vimeo_thumbnail" data-post="<?php echo $post->ID ?>" class="button-primary">Bild von Vimeo aktualisieren</button>
		<?php

		$field['instructions'] .= ob_get_clean();

		return $field;
	}


	public function load_immolive_termin( $value, $post_id, $field ) {

		if ( ! is_admin() || empty($value) ) {
			return $value;
		}

		$datetime = Carbon::createFromFormat( 'Y-m-d H:i:s', $value, 'UTC' );

		$datetime->setTimezone( 'Europe/Vienna' );

		return $datetime->format( 'Y-m-d H:i:s' );
	}


	public function save_immolive_termin( $value, $post_id, $field, $original ) {

		if(empty($value)) return $value;

		$datetime = Carbon::createFromFormat( 'Y-m-d H:i:s', $value, 'Europe/Vienna' );

		$datetime->setTimezone( 'UTC' );

		return $datetime->format( 'Y-m-d H:i:s' );
	}


	public function ir_add_options_pages() {
		// Check function exists.
		if ( ! function_exists( 'acf_add_options_page' ) ) {
			return;
		}

		// Register options page.
		acf_add_options_page( [
			'page_title' => __( 'Preroll Einstellungen' ),
			'menu_title' => __( 'Preroll Video Einstellungen' ),
			'menu_slug'  => 'preroll-settings',
			'capability' => 'edit_posts',
			'redirect'   => false,
		] );

		acf_add_options_sub_page( [
			'page_title'  => __( 'Zoom Einstellungen' ),
			'menu_title'  => __( 'Zoom' ),
			'parent_slug' => 'edit.php?post_type=immolive',
		] );

		acf_add_options_page( [
			'page_title' => 'Theme General Einstellungen',
			'menu_title' => 'Theme Einstellungen',
			'menu_slug'  => 'theme-general-settings',
			'capability' => 'edit_posts',
			'redirect'   => false,
		] );

		acf_add_options_sub_page( [
			'page_title'  => 'Login und Registrierung',
			'menu_title'  => 'Login/Registrierung',
			'parent_slug' => 'theme-general-settings',
		] );

	}

	public function update_duration( $value, $post_id, $field, $original ) {

		$data = ( new Vimeo() )->get_video_data( $post_id );

		return CarbonInterval::seconds( $data['duration'] )->cascade()->format( '%H:%I:%S' );

	}


}