<?php get_header();
$term = get_queried_object();
?>


    <div class="container mx-auto mt-20">
        <h1 class="font-sans text-5xl uppercase font-semibold text-gray-800 text-center">
            <a href="/lesen-2" class="underline">
				<?php _e( 'lesen', 'ir21' ) ?>
            </a>
        </h1>
    </div>

<?php get_template_part( 'banner', 'mega' ) ?>


    <div class="container mx-auto mt-20 px-5 lg:px-0 relative" x-data="{show:0}" x-init="window.onload = () => show = 1; inter = setInterval( () => { if(show < 3) { show++; } else { clearInterval(inter); } }, 500 ) ">
        <div class="flex flex-col lg:flex-row items-end">
            <div class="w-full lg:w-1/2 relative" style="background-color: <?php the_field( 'field_5c63ff4b7a5fb', $term ); ?>"
                 x-show.transition.fade.in="show > 0"
                 x-cloak>
                <p class="text-white font-serif text-5xl py-24 px-5 text-center"><?php echo $term->name ?></p>

				<?php if ( get_field( 'field_60da235237ec4', $term ) ): ?>
                    <div class="flex flex-col lg:absolute lg:top-100 lg:-mt-20 right-0 z-50 lg:max-w-xs shadow-2xl" x-show.transition.fade="show >= 3" x-cloak>
                        <p class="text-white">powered by</p>
                        <div class="bg-white flex justify-center w-full">
                            <a href="<?php echo get_field('field_5f9aeff4efa16', $term) ?>">
                                <img src="<?php the_field( 'field_60da235237ec4', $term ) ?>" class="w-auto h-auto">
                            </a>
                        </div>
                    </div>
				<?php endif; ?>

            </div>
            <div class="w-full lg:w-1/2 bg-gray-900 text-white -ml-5 -mb-5 p-5 relative" x-show.transition.fade="show >= 2" x-cloak>
				<?php echo $term->description ?>
            </div>
        </div>

    </div>

    <div class="container mx-auto mt-20 px-5 md:px-5">
		<?php if ( have_posts() ): ?>
            <div class="grid grid-cols-2 gap-10">
				<?php while ( have_posts() ): ?>
					<?php the_post(); ?>
                    <div class="col-span-2 md:col-span-1 relative">
                        <div class="relative block bg-primary-100 h-full" style="padding-top: 56%">
							<?php the_post_thumbnail( 'article', [
								'class'   => 'w-full h-auto max-w-full',
								'onerror' => "this.style.display='none'",
								'style'   => "margin-top:-56%",
							] ); ?>
                            <a href="<?php the_permalink(); ?>">
								<?php get_template_part( 'snippet', 'heading' ) ?>
                            </a>


                            <div class="absolute top-0 left-0 w-full h-auto">
                                <div class="flex justify-between p-5 text-white bg-gray-500 bg-opacity-25">
                                    <div>
                                        <p><?php echo get_the_time( 'd.m.Y' ) ?></p>
                                    </div>
                                    <div>
										<?php
										$terms = wp_get_post_terms( get_the_ID(), 'aktuelles_category' );
										$term  = array_shift( $terms );
										?>
                                        <a href="<?php echo get_term_link( $term ) ?>">
											<?php echo $term->name ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
				<?php endwhile; ?>
            </div>
		<?php endif; ?>
    </div>


    <div class="mt-48">
		<?php \reb_livestream_classes\Pagination::paginate();?>
    </div>


<?php get_footer();