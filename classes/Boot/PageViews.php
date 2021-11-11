<?php


namespace reb_livestream_classes\Boot;


use Google\Exception;

class PageViews {


	public function __construct() {

		add_action('wp_ajax_get_page_views_from_ga_api', [$this, 'get_page_views_from_ga_api']);
		add_action('wp_ajax_nopriv_get_page_views_from_ga_api', [$this, 'get_page_views_from_ga_api']);

	}

	function get_page_views_from_ga_api()
	{

		$KEY_FILE_LOCATION = get_stylesheet_directory() . '/immobilien-redaktion-264213-b40469a0e617.json';

		if (file_exists($KEY_FILE_LOCATION)) {

			$client = new \Google_Client();
			$client->setApplicationName("immobilien-redaktion-264213");
			$client->setAuthConfig($KEY_FILE_LOCATION);
			$client->setScopes(['https://www.googleapis.com/auth/analytics.readonly']);
			$analytics = new \Google_Service_Analytics($client);

			try {
				$results = $analytics->data_ga->get(
					'ga:192606539',
					'2005-01-01',
					'today',
					'ga:pageviews',
					[
						'filters' => 'ga:pagePath=@' . get_post_field('post_name', $_POST['id']),

					]
				);

				if (count($results->getRows()) > 0) {

					$rows = $results->getRows();
					$sessions = $rows[0][0];

					update_field('field_5f9ff32f68d04', $sessions, $_POST['id']);
					wp_die($sessions);
				} else {
					wp_die("No results found.");
				}

			} catch (Exception $e) {
				wp_die(get_field('field_5f9ff32f68d04', $_POST['id']));
			}


		} else {
			wp_die('no json');
		}
	}

}