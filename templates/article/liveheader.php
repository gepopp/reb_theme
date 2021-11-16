<?php
$prerolls = get_field( 'field_6097ef63e4e76', 'option' );
if ( $prerolls ) {
	shuffle( $prerolls );
	$preroll = array_shift( $prerolls );
} else {
	$preroll = [
		'preroll_id' => false,
	];
}

$lock   = true;
$termin = new \Carbon\Carbon( get_field( 'field_5ed527e9c2279' ) );

if ( $termin->addHour()->isPast() ) {
	$lock = false;
} else {
	if ( is_user_logged_in() ) {
		$subscriber = get_field( 'field_601451bb66bc3' );

		$user = wp_get_current_user();

		if ( $subscriber ) {
			foreach ( $subscriber as $sub ) {
				if ( trim($sub['user_email'] ) == $user->user_email ) {
					$lock = false;
				}
			}
		}
	}
}

?>
    <script>
        var preroll = <?php echo json_encode( $preroll ) ?>;
    </script>
    <div class="container mx-auto pr-0 pl-0">
		<?php if ( ! $lock ): ?>
            <div class="relative bg-black"
                 id="videoContainer"
                 x-data="liveplayer(
             preroll,
             <?php echo ! empty( get_field( 'field_616166cc0f4ff' ) ) ? get_field( 'field_616166cc0f4ff' ) : 'false' ?>,
             <?php echo ! empty( get_field( 'field_5fe2884da38a5' ) ) ? get_field( 'field_5fe2884da38a5' ) : 'false' ?>
             )"
                 x-init="initPlayer()"
                 @goto.window="jump($event.detail.chapter)"
            >
                <div style="padding:56.25% 0 0 0;position:relative;">
                    <div id="outer"></div>
                    <div :class="out == true ? 'fixed bottom-0 right-0 w-96 h-60 z-50 shadow-2xl m-10' : ''">
                        <iframe :src="src"
                                frameborder="0"
                                allow="autoplay; fullscreen; picture-in-picture"
                                allowfullscreen
                                style="position:absolute;top:0;left:0;width:100%;height:100%;"
                                id="player"
                                @load="setupPlayer()"
                        ></iframe>
                        <div class="absolute top-0 left-0 mt-3 bg-gray-800 text-white p-3 cursor-pointer flex lg:space-xEs-5" @click="window.open( preroll.link, '_blank' )" x-show="is_preroll">
                            <span class="hidden lg:block">Zum Werbetreibenden</span>
                            <svg class="w-4 lg:w-6 w-4 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                            </svg>
                        </div>
                        <div class="absolute bottom-0 right-0 p-3 lg:m-5 bg-gray-900 text-white cursor-pointer"
                             x-show="timer == 0 && is_preroll"
                             @click="loadSrc(true)"
                        >
                            Werbung überspringen
                        </div>
                    </div>
                </div>
            </div>
		<?php else: ?>
            <div>
				<?php the_post_thumbnail( 'full', [ 'class' => 'w-full h-auto' ] ); ?>
            </div>
		<?php endif; ?>
    </div>
<?php if ( $lock ): ?>
    <div class="fixed bottom-0 border-t border-black bg-white w-full z-50 shadow-lg animate__animated animate__slideInUp"
         x-data="{ show : false }"
         x-show="show"
         x-cloak
         x-init="setTimeout(() => { show = true }, 500 )"
    >
        <div class="container py-10 flex justify-between relative">

<!--            <div class="absolute top-0 right-0 pt-3 cursor-pointer" @click="show = false">-->
<!--                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">-->
<!--                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>-->
<!--                </svg>-->
<!--            </div>-->


            <div>
                <p>
			        <?php _e( 'Live at: ', 'reb_domain' ) ?>
			        <?php the_field( 'field_5ed527e9c2279' ); ?>
                </p>
                <p><?php the_title() ?></p>
            </div>



            <?php if ( is_user_logged_in() ): ?>


                <a href="<?php the_permalink(); ?>"
                   class="inline bg-logo text-center text-white font-bold py-3 px-5 focus:outline-none focus:shadow-outline flex items-center">
					<?php _e( 'participate', 'reb_domain' ) ?>
                </a>
			<?php else: ?>


                <div class="flex flex-col">
                    <p class="text-center"><?php _e( 'To participate:' ) ?></p>
	                <?php
	                global $wp;
	                $redirect = home_url( $wp->request )
	                ?>
                    <a href="<?php echo add_query_arg( 'redirect', $redirect, get_field( 'field_601bbffe28967', 'option' ) ) ?>"
                       class="inline bg-logo text-center text-white font-bold py-3 px-5 focus:outline-none focus:shadow-outline">
		                <?php _e( 'singin', 'reb_domain' ) ?>
                    </a>
                    <div class="flex justify-between">
                        <span><a href="<?php echo add_query_arg( 'redirect', $redirect, get_field( 'field_601bc00528968', 'option' ) ) ?>"><?php _e( 'or register now' ) ?></a></span>
                    </div>
                </div>


			<?php endif; ?>
        </div>
    </div>
<?php endif; ?>