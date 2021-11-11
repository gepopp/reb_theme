<div class="container mx-auto mt-20 px-5 lg:px-0 relative" x-data="{show:0}" x-init="window.onload = () => show = 1; inter = setInterval( () => { if(show < 3) { show++; } else { clearInterval(inter); } }, 500 ) ">
	<div class="flex flex-col lg:flex-row items-end">
		<div class="w-full lg:w-1/2 relative" style="background-color: <?php the_field( 'field_613b878f77b81', 'option' ); ?>"
		     x-show.transition.fade.in="show > 0"
		     x-cloak>
			<p class="text-white font-serif text-5xl py-24 px-5 text-center">Menschen</p>

			<?php if ( get_field( 'field_613b878f77bd6', 'option' ) ): ?>
				<div class="flex flex-col lg:absolute lg:top-100 lg:-mt-20 right-0 z-50 lg:max-w-xs shadow-2xl" x-show.transition.fade="show >= 3" x-cloak>
					<p class="text-white">powered by</p>
					<div class="bg-white flex justify-center w-full">
						<a href="<?php echo get_field( 'field_613b878f77bfd', 'option' ) ?>">
							<img src="<?php the_field( 'field_613b878f77bd6', 'option' ) ?>" class="w-auto h-auto">
						</a>
					</div>
				</div>
			<?php endif; ?>

		</div>
		<div class="w-full lg:w-1/2 bg-gray-900 text-white -ml-5 -mb-5 p-5 relative" x-show.transition.fade="show >= 2" x-cloak>
			<?php the_field( 'field_613b878f77bae', 'option' ); ?>
		</div>
	</div>
</div>