<?php


namespace reb_livestream_classes\Boot;


class Enqueue {


	public function __construct() {

		add_action( 'wp_enqueue_scripts', [ $this, 'ir_enqueue_scripts_and_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'ir_admin_script' ] );

	}


	public function ir_admin_script(  ) {

		wp_enqueue_script( 'ir_admin_script', reb_livestream_theme_URL . "/dist/admin.js", [], '1.0' );
	}


	public function ir_enqueue_scripts_and_styles() {

		$this->ir_dequeue_scripts();

		$min_ext = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		// CSS
		wp_enqueue_style(
			'reb_livestream_theme_css',
			reb_livestream_theme_URL . "/dist/main.css",
			[],
			reb_livestream_theme_VERSION,
			''
		);


		wp_enqueue_script(
			'reb_livestream_theme_js',
			reb_livestream_theme_URL . "/dist/main{$min_ext}.js",
			[],
			reb_livestream_theme_VERSION,
			true
		);

		wp_localize_script( 'reb_livestream_theme_js', 'messages', [
			'select'          => __( 'Bitte wählen', 'ir21' ),
			'enter_last_name' => __( 'Bitte geben Sie Ihren Nachnamen ein.', 'ir21' ),
			'password_min'    => __( "Bitte geben Sie mindestens 8 Zeichen ein.", 'ir21' ),
			'email_proofing'  => __( "E-Mail wird geprüft...", 'ir21' ),
			'email_exists'    => __( "Bitte geben Sie eine E-Mail Adresse ein die noch nicht registriert ist.", 'ir21' ),
			'email_invalid'   => __( "Bitte eine gültige E-Mail Adresse eingeben.", 'ir21' ),
			'rootapiurl'      => esc_url_raw( rest_url() ),
			'nonce'           => wp_create_nonce( 'wp_rest' ),
		] );


	}


	public function ir_dequeue_scripts() {
//		wp_dequeue_style( 'wp-block-library' );
//		wp_dequeue_style( 'wp-block-library-theme' );
//		wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS

		wp_dequeue_script( 'jquery' );
	}
}