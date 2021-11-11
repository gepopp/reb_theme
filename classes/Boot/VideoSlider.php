<?php


namespace reb_livestream_classes\Boot;


class VideoSlider {


	public function __construct() {
		add_action('wp_ajax_load_videos', [$this, 'load_videos']);
		add_action('wp_ajax_nopriv_load_videos', [$this, 'load_videos']);
	}


	public function load_videos()
	{

		$query = new \WP_Query([
			'post_type'           => 'post',
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'posts_per_page'      => 6,
			'paged'               => (int)$_POST['page'] + 1,
			'category__in'        => [(int)$_POST['cat']],
		]);

		$posts = [];

		if ($query->have_posts()):
			$runner = 1;
			while ($query->have_posts()):
				$query->the_post();

				$posts[] = [
					'ID'        => get_the_ID(),
					'permalink' => get_the_permalink(),
					'title'     => get_the_title(),
					'img'       => get_the_post_thumbnail_url(get_the_ID(), 'featured_small'),
				];
				$runner++;
			endwhile;
		endif;
		wp_die(json_encode($posts));
	}


}