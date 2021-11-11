<?php


if ( is_singular( 'aktuelle_presse' ) ) {
	$cat = wp_get_post_terms( get_the_ID(), 'aktuelles_category' );
} else {
	$cat = wp_get_post_categories( get_the_ID() );
}
$cat = array_shift( $cat );
$cat = get_category( $cat );


if ( $cat ):
	?>
    <div class="hidden lg:block">
        <script>
            function data() {
                return {
                    scrolled: 0,
                }
            }
        </script>

		<?php
		$color = '#5C97D0';
		$name  = '';

		if ( is_singular( 'zur_person' ) ) {
			$color = get_field( 'field_613b878f77b81', 'option' );
			$name  = 'Zur Person';
		} else {
			$color = get_field( 'field_5c63ff4b7a5fb', $cat );
			$name = $cat->name;
		}
		?>


		<?php if ( get_field( 'field_60da2369057c9', $cat ) ): ?>
            <!--box mit html banner-->
            <div class="relative h-64 hidden lg:block" id="powered"
                 x-data="data()"
                 @scroll.window="scrolled = document.getElementById('powered').offsetTop - window.pageYOffset"
            >
                <div class="absolute w-full h-full" :style="`top: ${ scrolled < 0 ? (scrolled * -1) + 100 : 0 }px;`">
                    <div class="h-full" style="background-color: <?php echo $color ?>">
                        <div id="scrollspy" class="flex flex-col justify-between">
                            <p class="p-5 font-serif text-2xl text-white"><?php echo $name ?></p>
                            <div class="bg-white">
								<?php the_field( 'field_60da2369057c9', $cat ); ?>
                            </div>
                        </div>
                    </div>
					<?php get_template_part( 'article', 'iconbar' ) ?>
                </div>
            </div>

		<?php elseif ( get_field( 'field_60da235237ec4', $cat ) ): ?>

            <!--box with banner-->
            <div class="relative hidden lg:block flex flex-col" id="powered"
                 x-data="data()"
                 @scroll.window="scrolled = document.getElementById('powered').offsetTop - window.pageYOffset"
            >
                <div class="absolute w-full h-full" :style="`top: ${ scrolled < 0 ? (scrolled * -1) + 100 : 0 }px;`">
                    <div id="scrollspy" class="flex flex-col justify-between"
                         style="background-color: <?php echo $color ?>">
                        <p class="px-5 pt-5 font-serif text-2xl text-white"><?php echo $name ?></p>
                        <p class="px-5 pb-5 text-white text-sm -mt-3">powered by</p>
                        <div class="bg-white border-b border-primary-100">
                            <a href="<?php echo get_field( 'field_5f9aeff4efa16', $cat ) ?>" class="text-center">
                                <img src="<?php the_field( 'field_60da235237ec4', $cat ); ?>" class="w-full h-auto p-5">
                            </a>
                        </div>
                    </div>
					<?php get_template_part( 'article', 'iconbar' ) ?>
                </div>
            </div>
		<?php else: ?>
        <div class="relative h-64 hidden lg:block" id="powered"
             x-data="data()"
             @scroll.window="scrolled = document.getElementById('powered').offsetTop - window.pageYOffset"
        >
            <!--                nur box oder mit sponsor logo-->
            <div class="absolute w-full h-full" :style="`top: ${ scrolled < 0 ? (scrolled * -1) + 100 : 0 }px;`">
                <div class="h-full" style="background-color: <?php echo $color ?>">
                    <div id="scrollspy" class="flex flex-col justify-between h-full">
                        <p class="p-5 font-serif text-2xl text-white"><?php echo $name ?></p>
                        <p class="p-5 text-white">
                            <a href="<?php echo get_category_link( $cat ) ?>">
                                <span class="text-white underline"><?php echo $cat->count ?? '' ?>&nbsp;<?php _e( 'Artikel', 'ir21' ) ?></span>
                            </a>
                        </p>
                    </div>
					<?php if ( get_field( 'field_5f9aeff4efa16', $cat ) ): ?>
                        <div class="absolute bottom-0 right-0 -ml-5 -mr-5 bg-white rounded-full w-24 h-24 flex flex-col items-center justify-center shadow-lg">
                            <a href="<?php echo get_field( 'field_5f9aeff4efa16', $cat ) ?>" class="text-center">
                                <p class="text-xs text-gray-900"><?php _e( 'powered by', 'ir21' ) ?></p>
                                <img src="<?php echo get_field( 'field_5f9aefd116e2e', $cat ) ?>" class="w-24 h-auto px-5">
                            </a>
                        </div>
					<?php endif; ?>
                </div>
				<?php get_template_part( 'article', 'iconbar' ) ?>
            </div>
			<?php endif; ?>
        </div>
    </div>
<?php endif;