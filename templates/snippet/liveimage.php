<?php
/**
 * @var $term
 */
extract($args);

if ( has_post_thumbnail() ) {
	the_post_thumbnail( 'article', [
		'class' => 'w-full h-auto max-w-full',
	] );
} else {
	$attachment_id = get_field( 'field_614a3cf01f64c', 'immolive_category_' . $term->term_id );
	echo wp_get_attachment_image( $attachment_id, 'featured', false, [ 'class' => 'w-full h-auto' ] );
}