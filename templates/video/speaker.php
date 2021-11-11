<?php
$teilnehmer = get_field( 'field_614ad5e239622' );
if ( ! empty( $teilnehmer ) ):
	?>
	<div class="">
		<h3 class="text-white text-3xl font-serif text-center mb-10">Die Expert*innen im Livestream</h3>
		<div class="flex space-x-5 flex-wrap w-full justify-center">
			<?php foreach ( $teilnehmer as $item ): ?>
				<div class="border-b border-white pb-3 mb-3 w-48">
					<a href="<?php echo get_the_permalink( $item ) ?>" class="no-underline" style="text-decoration: none !important;">
						<?php
						echo get_the_post_thumbnail( $item, 'thumbnail', [
							'class'   => 'w-48 h-auto rounded-full p-5 border-2 border-white',
							'onerror' => "this.style.display='none'",
						] );

						if ( ! has_post_thumbnail( $item ) ):?>
							<div class="w-48 h-48 rounded-full p-5 border-2 border-primary-100"></div>
						<?php endif; ?>
						<div class="flex flex-col text-center mt-10">
							<?php $name = get_field( 'field_613c53f33d6b8', $item ) . ' ' . get_field( 'field_613b8ca49b06b', $item ) ?>
							<?php $position = ! empty( get_field( 'field_613c54063d6b9', $item ) ) ? get_field( 'field_613c54063d6b9', $item ) . ' - ' . get_field( 'field_613b8caa9b06c', $item ) : '&nbsp;' ?>
							<h3 class="text-white text-3xl mb-0 font-serif font-semibold no-underline"><?php echo $name ?></h3>
							<p class="text-white text-sm italic mb-5 no-underline"><?php echo $position ?></p>
						</div>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>

<?php endif; ?>