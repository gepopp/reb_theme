<?php
/**
 * @var $categories
 */
extract( $args );
?>


<div class="container mx-auto">
    <div class="my-5 w-full text-center">
        <h1 class="text-3xl font-bold mb-5">
			<?php _e( 'Upcoming Events', 'reb_domain' ) ?>
        </h1>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 border-y border-primary-100">
		<?php
		$runner   = 1;
		$date_now = date( 'Y-m-d H:i:s' );
		$query    = new WP_Query( [
			'post_type'      => 'livestream',
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

			$terms = wp_get_post_terms( get_the_ID(), 'Livestream_category' );
			$term  = array_shift( $terms );

			$ics = get_field( 'field_6143982f5f5f2' );

			$teilnehmer = get_field( 'field_601451bb66bc3' );
			if ( ! $teilnehmer ) {
				$participants = 0;
			} else {
				$participants = count( $teilnehmer );
			}
			?>
            <div class="card mb-20 last:mb-0">
                <div class="relative">
                    <a href="<?php the_permalink(); ?>" class="block bg-primary-100 h-full">
						<?php
						if ( has_post_thumbnail() ) {
							the_post_thumbnail( 'full', [
								'class' => 'w-full h-auto max-w-full',
							] );
						} else {
							$attachment_id = get_field( 'field_614a3cf01f64c', 'immolive_category_' . $term->term_id );
							echo wp_get_attachment_image( $attachment_id, 'featured', false, [ 'class' => 'w-full h-auto' ] );
						}

						?>
                    </a>
                </div>
                <div class="w-full p-3 bg-white border border-primary-100">
                    <div class="flex justify-between border-b font-semibold text-primary-100 border-primary-100 pb-3 mb-3">
                        <span class="py-2"><?php echo $starts->format('d.m.Y H:i') ?></span>
	                    <?php if(get_field('field_6196833f81731')): ?>
                            <a href="<?php the_field('field_6196833f81731'); ?>" class="inline-block bg-red-800 text-white py-2 px-3">
			                    <?php _e('survey', 'reb_domain') ?>
                            </a>
	                    <?php endif; ?>
                    </div>
                    <div class="text-gray-900 py-5">
                        <h2 class="text-lg mb-4 uppercase font-bold"><?php the_title() ?></h2>
                        <p class="text-gray-900 line-clamp-3">
							<?php echo get_the_excerpt(); ?>
                        </p>

                        <div class="flex justify-end mt-3">
                        <div>
                            <a href="<?php the_permalink(); ?>" class="inline py-2">
		                        <?php _e( 'more', 'reb_domain' ) ?>
                            </a>
                        </div>



                        </div>
                    </div>
                    <div class="border-t border-primary-100 py-3">

						<?php if ( is_user_logged_in() ): ?>

							<?php
							$is_participant = [];
							$participants   = get_field( 'field_601451bb66bc3' );

							$user = wp_get_current_user();

							if ( $participants ) {
								$is_participant = array_filter( $participants, function ( $p ) {
									$user = wp_get_current_user();
									if ( trim( $p['user_email'] ) == $user->user_email ) {
										return $p;
									}
								} );
							}

							if ( empty( $is_participant ) ):
                                get_template_part('immolive', 'participatebutton');
                            else: ?>

                                <p class="text-center py-2 ">
                                    <?php _e('your are allready subscribed', 'reb_domain') ?>
                                </p>

							<?php endif; ?>


						<?php else: ?>

                            <p class="text-center mb-4"><?php _e( 'To participate:' ) ?></p>
							<?php
							global $wp;
						$redirect = home_url( $wp->request )
							?>
                            <a href="<?php echo add_query_arg( 'redirect', $redirect, get_field( 'field_601bbffe28967', 'option' ) ) ?>"
                               class="block w-full bg-logo text-center text-white font-bold py-2 px-4 focus:outline-none focus:shadow-outline">
								<?php _e( 'singin', 'reb_domain' ) ?>
                            </a>
                            <div class="flex justify-between">
                                <span><?php _e( 'No account?', 'reb_domain' ) ?></span>
                                <span><a href="<?php echo add_query_arg( 'redirect', $redirect, get_field( 'field_601bc00528968', 'option' ) ) ?>"><?php _e( 'register now' ) ?></a></span>
                            </div>
						<?php endif; ?>
                    </div>
                </div>
            </div>
		<?php endwhile; ?>
    </div>
	<?php endif; ?>
</div>
