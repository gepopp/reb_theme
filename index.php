<?php
/**
 * The default single page template.
 *
 * @author Freeshifter LLC
 * @since  1.0.0
 */

namespace reb_livestream_theme;

get_header();


$query = new \WP_Query( [
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'posts_per_page'      => 3,
] );
get_template_part( 'part', 'threearticle', [ 'query' => $query ] );

get_template_part( 'banner', 'mega' );

get_template_part( 'part', 'liverow', ['when' => 'past'] );

get_template_part( 'banner', 'fourbanner' );

$query = new \WP_Query( [
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'posts_per_page'      => 3,
	'offset'              => 3,
] );
get_template_part( 'part', 'threearticle', [ 'query' => $query ] );


get_template_part( 'part', 'liverow' );

get_template_part( 'banner', 'thirds2' );

get_template_part( 'part', 'aktuellesrow' );

get_template_part( 'part', 'projekterow' );

?>
</div>

<?php
get_footer();
