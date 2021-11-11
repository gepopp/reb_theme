<?php

namespace reb_livestream_classes\Boot;

class TemplateFilters {


	public function __construct() {


		foreach ( $this->get_folders() as $folder ) {

			$folder = explode( '/', $folder );

			add_action( 'get_template_part_' . array_pop( $folder ), function ( $slug, $name, $args ) {
				locate_template( [ 'templates/' . $slug . '/' . $name . '.php' ], true, false, $args );
			}, 10, 3 );
		}
	}


	protected function get_folders(): array {

		$templates_folder = trailingslashit( get_template_directory() ) . 'templates';

		return glob( $templates_folder . '/*', GLOB_ONLYDIR );


	}

}