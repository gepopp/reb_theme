<?php


namespace reb_livestream_classes\Boot;


use reb_livestream_theme\FormSession;

class TemplateRedirects {


	public function __construct() {

		add_action('template_redirect', [$this, 'redirect']);
		add_action('wp_logout', [$this, 'logout_redirect']);
		add_action( 'init', [$this, 'block_post_actions']);

	}


	public function logout_redirect(){

		wp_redirect( home_url() );
		exit();

	}


	public function redirect(){

		if(is_tag('immolive')){
			wp_safe_redirect(home_url('diskutieren'));
		}


		if( (   is_page_template('pagetemplate-login.php') ||
			    is_page_template('pagetemplate-resend-activation.php') ||
			    is_page_template('pagetemplate-register.php') )
		    && is_user_logged_in()){
			wp_safe_redirect(get_field('field_601bc4580a4fc', 'option'));
		}

		if( (is_page_template('pagetemplate-passwort-vergessen.php') || is_page_template('pagetemplate-passwort-reset.php')) && is_user_logged_in()){
			wp_safe_redirect(get_field('field_601bc4580a4fc', 'option'));
		}

		if(is_page_template('pagetemplate-profil.php') && !is_user_logged_in()){
			wp_safe_redirect(get_field('field_601bbffe28967', 'option'));
		}

	}

	public function block_post_actions(){

		global $FormSession;
		$FormSession = \reb_livestream_classes\FormSession::session();

		global $wp_query;

		$allowed = ['update_profile', 'subscribe_immolive', 'resend_activation', 'frontent_logout', 'update_profile_image', 'get_immolive_ics', 'save_zur_person_update'];
		$action = $_REQUEST['action'] ?? '';

		if(is_admin() && !wp_doing_ajax() && !in_array($action, $allowed)){

			$user = wp_get_current_user();
			if(in_array('registered', $user->roles) || in_array('subscriber', $user->roles)){

				$wp_query->set_404();
				status_header( 404 );
				get_template_part( 404 );
				exit();
			}
		}
	}

}