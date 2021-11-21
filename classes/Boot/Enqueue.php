<?php


namespace reb_livestream_classes\Boot;


class Enqueue {


	public function __construct() {

		add_action( 'wp_enqueue_scripts', [ $this, 'ir_enqueue_scripts_and_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'ir_admin_script' ] );

	}


	public function ir_admin_script() {

		wp_enqueue_script( 'ir_admin_script', reb_livestream_theme_URL . "/dist/admin.js", [], '1.0' );
	}


	public function ir_enqueue_scripts_and_styles() {

		$this->ir_dequeue_scripts();

		$min_ext = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

		// CSS
		wp_enqueue_style(
			'reb_livestream_theme_font',
			reb_livestream_theme_URL . "/assets/fonts/helvetica-neue-lt-pro/style.css",
			[],
			reb_livestream_theme_VERSION,
			''
		);

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
			'select'                    => __( 'Please select', 'reb_domain' ),
			'enter_last_name'           => __( 'Please enter your name.', 'reb_domain' ),
			'password_min'              => __( "Please enter at least 8 characters.", 'reb_domain' ),
			'email_proofing'            => __( "Email is checked...", 'reb_domain' ),
			'email_exists'              => __( "Please enter a email address that is not registered.", 'reb_domain' ),
			'email_invalid'             => __( "Please enter a valid email address.", 'reb_domain' ),
			'email_not_registered'      => __( 'This address is not regstered.', 'reb_domain' ),
			'email_sent'                => __( 'We sent you an email, please check your inbox.', 'reb_domain' ),
			'rootapiurl'                => esc_url_raw( rest_url() ),
			'nonce'                     => wp_create_nonce( 'wp_rest' ),
			'participate'               => __( 'participate', 'reb_domain' ),
			'participate_saving'        => __( 'saving', 'reb_domain' ),
			'participate_saved'         => __( 'subscription saved', 'reb_domain' ),
			'participate_sending'       => __( 'sending confirmation', 'reb_domain' ),
			'participate_sending_error' => __( 'failed sending confirmation', 'reb_domain' ),
			'participate_completed'     => __( 'subscription completed', 'reb_domain' ),
			'participate_saving_error'  => __( 'Error saving your subscription, please refresh this page and try again', 'reb_domain' ),
		] );


	}


	public function ir_dequeue_scripts() {
//		wp_dequeue_style( 'wp-block-library' );
//		wp_dequeue_style( 'wp-block-library-theme' );
//		wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS

		wp_dequeue_script( 'jquery' );
	}
}