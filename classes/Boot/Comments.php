<?php


namespace reb_livestream_classes\Boot;


class Comments {


	public function __construct() {

		add_action( 'rest_api_init', [ $this, 'comment_child_count' ] );
		add_action( 'wp_insert_comment', [ $this, 'ajax_insert_comment' ], 10, 2 );
	}

	public function ajax_insert_comment( $id, $comment ) {

		wp_schedule_single_event( time() + 30,
			'scheduledAdminNotice',
			[ 'comment' => $comment ] );

		return $comment;

	}

	public function comment_child_count() {
		\register_rest_field( 'comment', 'child_count', [
			'get_callback' => function ( $comment ) {

				$children = get_comments( [ 'parent' => $comment['id'] ] );

				return (int) count( $children );
			},
			'schema'       => [
				'description' => 'List number of comments attached to this post.',
				'type'        => 'integer',
			],
		] );
	}
}