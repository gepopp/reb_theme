<?php
$user   = wp_get_current_user();
$post   = get_the_ID();
$termin = new \Carbon\Carbon( get_field( 'field_5ed527e9c2279' ) );

?>

<div
        x-data="readingLog(<?php echo $user->ID ?? false ?>, <?php echo $post ?>)"
        x-init="getmeasurements();"
        @scroll.window.debounce.1s="amountscrolled()"
        @resize.window="getmeasurements()"
        ref="watched"
>
	<?php
	$termin = get_field( 'field_5ed527e9c2279' );
	$carbon = new \Carbon\Carbon( $termin );
	?>

	<?php get_template_part( 'article', 'liveheader' ); ?>

    <div class="container mx-auto text-black lg:px-0" x-data="{ showMore: false }">
        <h1 class="text-3xl font-bold mt-5 mb-2">
			<?php echo get_the_title() ?>
        </h1>
        <div class="hidden sm:block">
			<?php get_template_part( 'video', 'meta', [ 'mode' => 'light' ] ) ?>
        </div>
    </div>


	<?php
	$experts = get_field( 'field_6197cdea9e8c3' );

	if ( $experts ): ?>

        <div class="container mx-auto text-black px-0">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-<?php echo count($experts) <= 3 ? 3 : 5 ?> gap-3 py-3 lg:py-0">
				<?php
				foreach ( $experts as $expert ):
					$iframe = $expert['experten_video_'];
					$iframe = str_replace( '"640"', '"100%"', $iframe );
					$iframe = str_replace( '"360"', '"auto"', $iframe );
					?>
                    <div class="expert-video">
						<?php echo $iframe ?>
                    </div>
				<?php endforeach; ?>
            </div>
            <hr class="py-3">
        </div>

	<?php endif; ?>


    <div class="container mx-auto text-black lg:px-0" x-data="{ showMore: false }">
        <div class="grid grid-cols-6 gap-8">
            <div class="col-span-5">
                <div class="" :class="showMore ? '' : 'line-clamp-3'">
					<?php the_content(); ?>
                </div>
                <div class="flex justify-end w-full" @click="showMore = true" x-show.transition="!showMore">
                    <p class="uppercase cursor-pointer flex space-x-3">
                        <span><?php _e( 'show more', 'reb_institute' ) ?></span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </p>
                </div>
                <div class="flex justify-end w-full" @click="showMore = false" x-show.transition="showMore">
                    <p class="uppercase cursor-pointer flex space-x-3">
                        <span><?php _e( 'show less', 'reb_institute' ) ?></span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                    </p>
                </div>
                <hr class="py-3">
				<?php
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
				?>
            </div>
            <div class="content col-span-6 lg:col-span-1">
				<?php get_template_part( 'video', 'moderator' ) ?>
				<?php get_template_part( 'video', 'speaker' ) ?>
            </div>
        </div>
    </div>