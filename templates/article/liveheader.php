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
		$user       = wp_get_current_user();
		foreach ( $subscriber as $sub ) {
			if ( $sub['user_email'] == $user->user_email ) {
				$lock = false;
			}
		}
	}
}

?>
<script>
    var preroll = <?php echo json_encode( $preroll ) ?>;

    function isInViewport(el) {
        const rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 && rect.left >= 0
        );
    }
</script>
<div class="container mx-auto">
    <div class="hidden lg:block"></div>
    <div class="col-span-5 lg:col-span-3  py-5">

        <div class="grid grid-cols-4 gap-5">
			<?php if ( ! $lock ): ?>

                <div class="relative col-span-4 lg:col-span-3"
                     id="videoContainer"
                     x-data="liveplayer(preroll,
             <?php echo ! empty( get_field( 'field_616166cc0f4ff' ) ) ? get_field( 'field_616166cc0f4ff' ) : 'false' ?>,
             <?php echo ! empty( get_field( 'field_5fe2884da38a5' ) ) ? get_field( 'field_5fe2884da38a5' ) : 'false' ?>)"
                     x-init="init()"
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
                            <div class="absolute top-0 left-0 mt-3 bg-gray-800 text-white p-3 cursor-pointer flex lg:space-x-5" @click="window.open( preroll.link, '_blank' )" x-show="is_preroll">
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
                <div class="col-span-4 lg:col-span-3" style="background-image: url(<?php echo get_the_post_thumbnail_url( get_the_ID(), 'full' ) ?>)">
                    <div class="w-full min-h-full h-auto bg-white bg-opacity-90 flex justify-center items-center px-5 lg:px-5 z-50 py-10">
                        <div class="max-w-2xl">
							<?php get_template_part( 'immolive', 'subscribeform', [ 'id' => get_the_ID() ] ) ?>
                        </div>
                    </div>
                </div>
			<?php endif; ?>
            <div class="col-span-4 lg:col-span-1 border border-primary-100 p-3 xs:col-start-1">
                <div class="flex items-center justify-center h-full">

                    <div class="grid grid-cols-2 gap-5 h-full">
                        <div class="col-span-2 md:col-span-1 lg:hidden text-white">
							<?php get_template_part( 'video', 'chapters' ) ?>
                        </div>
                        <div class="col-span-2 md:col-span-1 lg:col-span-2">
                           <iframe src="<?php echo get_stylesheet_directory_uri() ?>/banner_ehl/600x1200.php" class="w-full h-full"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hidden lg:block"></div>
</div>