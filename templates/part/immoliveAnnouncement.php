<?php
/**
 * @var $categories
 */
extract( $args );
?>


<div class="container mx-auto p-5">

    <div class="my-5 w-full text-center">
        <h1 class="inline font-serif text-3xl font-semibold text-center text-white"
            style="background: linear-gradient(0deg, #5C97D0 0%, #5C97D0 50%, transparent 50%, transparent 100%);">
            Kommende Livestreams
        </h1>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 border-y border-primary-100">
		<?php
		$runner   = 1;
		$date_now = date( 'Y-m-d H:i:s' );
		$query    = new WP_Query( [
			'post_type'      => 'immolive',
			'posts_per_page' => 3,
			'meta_query'     => [
				[
					'key'     => 'termin',
					'compare' => '>=',
					'value'   => $date_now,
					'type'    => 'DATETIME',
				],
			],
			'order'          => 'ASC',
			'meta_key'       => 'termin',
			'orderby'        => 'meta_value',
		] );
		if ( $query->have_posts() ): ?>
		<?php while ( $query->have_posts() ):
			$query->the_post();


			$starts = new \Carbon\Carbon( get_field( 'field_5ed527e9c2279' ) );
			$starts->setTimezone( 'UTC' );
			\Carbon\Carbon::setLocale( 'de' );

			$terms = wp_get_post_terms( get_the_ID(), 'immolive_category' );
			$term  = array_shift( $terms );

			$ics = get_field( 'field_6143982f5f5f2' );

			?>
            <div class="card mb-20 last:mb-0">
                <div class="relative">
                    <a href="<?php the_permalink(); ?>" class="block bg-primary-100 h-full image-holder">
						<?php
						if ( has_post_thumbnail() ) {
							the_post_thumbnail( 'article', [
								'class' => 'w-full h-auto max-w-full',
							] );
						} else {
							$attachment_id = get_field( 'field_614a3cf01f64c', 'immolive_category_' . $term->term_id );
							echo wp_get_attachment_image( $attachment_id, 'featured', false, ['class' => 'w-full h-auto'] );
						}

						?>
						<?php get_template_part( 'snippet', 'heading', [ 'size' => 'small' ] ) ?>
                    </a>
                </div>
                <div class="w-full p-3 bg-white border border-primary-100">
                    <div class="flex justify-between border-b font-semibold text-primary-100 border-primary-100 mb-3">
                        <span><?php echo 'Live ' . $starts->diffForHumans() ?></span>
                        <span><?php echo $term->name; ?></span>
                    </div>
                    <div class="text-gray-900 py-5">
                        <p class="text-gray-900 line-clamp-3">
							<?php echo get_the_excerpt(); ?>
                        </p>
                    </div>
                    <div class="border-t border-primary-100">
						<?php get_template_part( 'immolive', 'subscribeform', [ 'id' => get_the_ID() ] ) ?>
                    </div>
                </div>
            </div>
		<?php endwhile; ?>
    </div>
	<?php endif; ?>
</div>
