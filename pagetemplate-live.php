<?php
/**
 * Template Name: Landing Live
 */
get_header();
the_post();

$date_now = date( 'Y-m-d H:i:s' );


$categories = get_terms( 'Livestream_category' );

foreach ( $categories as &$category ) {
	$category->order = get_field( 'field_614199d8b8225', 'Livestream_category_' . $category->term_id );
}

usort( $categories, function ( $a, $b ) {
	if ( $a->order == $b->order ) {
		return 0;
	}

	return ( $a->order < $b->order ) ? - 1 : 1;
} );
?>

<?php get_template_part( 'part', 'immoliveAnnouncement', [ 'categories' => $categories ] ); ?>


    <div class="container mx-auto">

        <div class="my-5 w-full text-center">
            <h1 class="text-3xl font-bold mb-5">
			    <?php _e('Past Events', 'reb_domain') ?>
            </h1>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

			<?php
			$query = new WP_Query( [
				'post_type'      => 'livestream',
				'posts_per_page' => 8,
				'meta_query'     => [
					[
						'key'     => 'termin',
						'compare' => '<=',
						'value'   => $date_now,
						'type'    => 'DATETIME',
					],
				],
			] );
			if ( $query->have_posts() ):
				while ( $query->have_posts() ): ?>
					<?php $query->the_post(); ?>
                    <div class="card mb-20 last:mb-0">
                        <div class="relative">
                            <a href="<?php the_permalink(); ?>" class="block bg-primary-100 h-full">
								<?php the_post_thumbnail( 'full', [
									'class' => 'w-full h-auto max-w-full',
								] ); ?>
                            </a>
                        </div>
                        <div class="w-full p-3 bg-white border border-primary-100">
                            <div class="text-gray-900 py-5">
                                <h2 class="text-lg mb-4 uppercase font-bold"><?php the_title() ?></h2>
                                <p class="text-gray-900 line-clamp-3">
									<?php echo get_the_excerpt(); ?>
                                </p>
                            </div>
                            <div class="border-t border-primary-100 py-3">
                                <a href="<?php the_permalink(); ?>"
                                   class="block w-full bg-logo text-center text-white font-bold py-2 px-4 focus:outline-none focus:shadow-outline">
                                    <?php _e('watch now', 'reb_domain') ?>
                                </a>
                            </div>
                        </div>
                    </div>
				<?php endwhile; ?>
			<?php endif; ?>
        </div>
    </div>
<?php
get_footer();



