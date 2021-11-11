<?php
add_shortcode( 'koepfe', function ( $atts = [] ) {
	/**
	 * @var $ids
	 * @var $tag
	 * @var $layout
	 * @var $rounded
	 *
	 */
	$ids     = "";
	$tag     = "";
	$layout  = 'alternate';
	$rounded = '';


	if ( is_array( $atts ) ) {
		extract( $atts );
	}

	$arg = [
		'post_type'           => 'zur_person',
		'posts_per_page'      => - 1,
		'post_status'         => 'publish',
		'ignore_sticky_posts' => 1,
	];

	if ( ! empty( $ids ) ) {
		$arg['post__in'] = explode( ',', $ids );
		$arg['orderby']  = 'post__in';
	} else {
		$arg['posts_per_page'] = 5;
	}


	$query = new WP_Query( $arg );

	ob_start();
	$runner = 1;
	if ( $query->have_posts() ):
		?>
        <div class="container spaced border-t-2 border-primary-100">
			<?php
			while ( $query->have_posts() ):
				$query->the_post();
				?>
                <div class="py-10 px-5 border-b-2 border-primary-100">
                    <div class="flex flex-wrap lg:flex-nowrap">
						<?php
						$order = '';
						if ( $layout == 'right' ) {
							$order = 'lg:order-last';
						}
						if ( $layout == 'alternate' && $runner % 2 == 0 ) {
							$order = 'lg:order-last';
						}

						?>
                        <div class="lg:flex-shrink-0 w-full lg:w-1/4 <?php echo $order ?>">
                            <figure>
								<?php
								echo get_the_post_thumbnail( get_the_ID(), 'thumbnail', [ 'class' => 'max-w-full w-full h-auto rounded-full border-2 border-primary-100 p-5' ] );
								?>
                            </figure>
                        </div>
                        <div class="h-full text-primary-100 w-full  lg:w-3/4 <?php echo $layout == 'right' || ( $layout == 'alternate' && $runner % 2 == 0 ) ? ' lg:pr-10' : ' lg:pl-10' ?> pt-10 lg:pt-0">
                            <div class="flex flex-col">
                                <h3 class="text-3xl mb-0"><?php the_field( 'field_613c53f33d6b8' ) ?>&nbsp;<?php the_field( 'field_613b8ca49b06b' ) ?></h3>
                                <p class="text-sm italic mb-5"><?php the_field( 'field_613c54063d6b9' ); ?>&nbsp;-&nbsp;<?php the_field( 'field_613b8caa9b06c' ); ?></p>
                                <p class="line-clamp-3 flex-1 text-gray-900"><?php echo get_the_excerpt(); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="flex <?php echo $layout == 'right' || ( $layout == 'alternate' && $runner % 2 == 0 ) ? ' justify-start' : ' justify-end' ?> w-full mt-auto">
                        <a href="<?php the_permalink(); ?>" class="py-3 px-10 bg-primary-100 text-white font-semibold text-center">
                            <span class="text-white">Weitere Details</span>
                        </a>
                    </div>
                </div>
				<?php
				$runner ++;
			endwhile;
			?>
        </div>
        <div class="lg:pr-10 lg:pl-10 justify-end justify-start"></div>

	<?php
	endif;

	return ob_get_clean();
} );