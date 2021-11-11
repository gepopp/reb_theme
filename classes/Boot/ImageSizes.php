<?php


namespace reb_livestream_classes\Boot;


class ImageSizes {


	public function __construct() {

		add_action('after_setup_theme', [$this, 'add_image_sizes'] );

	}


	public function add_image_sizes(){

		add_image_size('custom-thumbnail', 800, 600, true);
		add_image_size('featured', 800, 450, true);
		add_image_size('featured_small', 300, (300 / 16) * 9, true);

		add_image_size('author_extra_small', 24, 24, true);
		add_image_size('author_small', 48, 48, true);
		add_image_size('author_large', 96, 96, true);


		add_image_size('horizontal_box', 370, 265, true);
		add_image_size('article', 600, 450, true);
		add_image_size('article-portrait', 250, 333, true);
		add_image_size('portrait', 300);
		add_image_size('xs', 100, 100, true);

	}
}