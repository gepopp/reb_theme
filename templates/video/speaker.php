<?php
$teilnehmer = get_field( 'field_614ad5e239622' );
if ( ! empty( $teilnehmer ) ):
	?>
    <div class="text-3xl font-bold my-5">
        <h3 class="text-3xl font-bold my-5 text-center">
			<?php _e( 'The experts in this event' ) ?>
        </h3>
        <div class="flex space-x-5 flex-wrap w-full justify-center">
			<?php foreach ( $teilnehmer as $item ): ?>
                <div class="pb-3 mb-3 w-48">

                    <div class="relative">

						<?php
						$linkedin = get_field( 'field_61979a17c1a5a', $item );
						if ( $linkedin ):
							?>
                            <div class="absolute bottom-0 left-0 rounded-full p-1 bg-white border border-black">
                                <a href="<?php echo $linkedin ?>">
                                    <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/linkedin-rounded.png" class="w-10 h-auto rounded-full">
                                </a>
                            </div>
						<?php endif;
						echo get_the_post_thumbnail( $item, 'thumbnail', [
							'class'   => 'w-48 h-auto rounded-full p-3 border-2 border-black',
							'onerror' => "this.style.display='none'",
						] );

						$logo = get_field( 'field_61979a04c1a59', $item );
						if ( $logo ):
							?>
                            <div class="absolute top-0 right-0 rounded-full p-1 bg-white border border-black">
                                <a href="<?php echo get_field('field_617040c6f50bc', $item) ?>">
                                    <img src="<?php echo $logo['sizes']['thumbnail'] ?>" class="w-12 h-auto rounded-full">
                                </a>
                            </div>
						<?php endif; ?>
                    </div>


                    <div class="flex flex-col text-center mt-10">
						<?php $name = get_field( 'field_613c53f33d6b8', $item ) . ' ' . get_field( 'field_613b8ca49b06b', $item ) ?>
						<?php $position = ! empty( get_field( 'field_613c54063d6b9', $item ) ) ? get_field( 'field_613c54063d6b9', $item ) . ' - ' . get_field( 'field_613b8caa9b06c', $item ) : '&nbsp;' ?>
                        <h3 class="text-black text-xl mb-0 font-semibold no-underline"><?php echo $name ?></h3>
                        <p class="text-black text-sm italic mb-5 no-underline"><?php echo $position ?></p>
                    </div>
                    </a>
                </div>
			<?php endforeach; ?>
        </div>
    </div>

<?php endif; ?>