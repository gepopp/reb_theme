<?php

use Overtrue\Socialite\SocialiteManager;
use function reb_livestream_theme\load_vimeo_image;

/**
 * Template Name: Diskutieren
 */
get_header();
the_post();

$date  = date( 'Ymd' );
$query = new WP_Query( [
	'post_type'      => 'immolive',
	'post_status'    => 'publish',
	'posts_per_page' => 2,
	'meta_query'     => [
		'relation' => 'AND',
		[
			'key'     => 'il_datum',
			'value'   => $date,
			'compare' => '>=',
		],
	],
	'order'          => 'ASC',
	'meta_key'       => 'il_datum',
	'orderby'        => 'meta_value_num',
] );
$count = $query->post_count;

$runner = 1;

if ( $query->have_posts() ):
	while ( $query->have_posts() ):
		$query->the_post();

		date_default_timezone_set( 'Europe/Vienna' );
		if ( (int) date( 'hi' ) > 1601 && $runner == 1 ) {
			$runner ++;
			continue;
		}


		get_template_part( 'snippet', 'event' );

		?>
        <div class="lg:h-screen-75 flex lg:-mx-5">
            <div class="lg:w-1/2 bg-white h-full relative flex justify-center items-center">
                <div class="lg:w-3/4 xl:w-1/2">
                    <div class="mt-20 mb-10 lg:hidden" x-data="counter('<?php the_field( 'field_5ed527e9c2279' ) ?>')" x-init="count()">
						<?php get_template_part( 'immolive', 'counter' ) ?>
                    </div>
                    <p class="font-semibold hidden lg:block px-5 lg:px-0">Diese Livestream startet am <?php echo get_field( 'field_5ed527e9c2279' ) ?> Uhr.</p>
                    <h1 class="text-3xl font-semibold font-serif leading-tight px-5 lg:px-0 pb-5"><?php the_title() ?></h1>
                    <div class="px-5 lg:px-0"><?php the_content(); ?></div>
                    <div x-data="subscribe(<?php echo get_the_ID() ?>, <?php echo is_user_logged_in() ?>)" class="mt-10">
                        <div x-show.transition.in.fade="!showSubscriptionForm">


							<?php
							$subscribed  = false;
							$registrants = get_field( 'field_601451bb66bc3' );


							$user = wp_get_current_user();
							if ( $registrants ) {
								foreach ( $registrants as $registrant ) {
									if ( $registrant['user_email'] == $user->user_email ) {
										$subscribed = true;
									}
								}
							}
							if ( ! $subscribed || ! is_user_logged_in() ):
								?>
                                <a class="block bg-primary-100 text-white font-semibold text-center shadow-xl py-3 my-5 text-xl cursor-pointer" @click="showSubscriptionForm = true">jetzt anmelden</a>
							<?php else: ?>
                                <p class="my-3 p-3 border border-primary-100 text-primary-100 text-xl text-center w-full">
                                    Sie sind bereits zu dieser Veranstaltung angemeldet.
                                </p>
							<?php endif; ?>
                        </div>
                        <div class="relative p-5 xl:p-0" x-show.transition.in.fade="showSubscriptionForm" x-cloak>
                            <div class="absolute top-0 right-0 -mt-5 bg-primary-100 bg-opacity-5" @click="showSubscriptionForm = false">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </div>
                            <div class="bg-primary-100 bg-opacity-5 p-5">
                                <div x-show="isLoggedin">
									<?php $user = wp_get_current_user(); ?>
                                    <h2 class="font-serif font-semibold text-xl mb-4"><?php echo $user->first_name ?> <?php echo $user->last_name ?>, wir freuen uns auf Ihre Teilnahme!</h2>
                                    <form action="<?php echo admin_url( 'admin-post.php' ) ?>" method="post" @submit.prevent="submit()" x-ref="subsribe">
										<?php wp_nonce_field( 'subscribe_immolive', 'subscribe_immolive' ) ?>
                                        <input type="hidden" name="action" value="subscribe_immolive">
                                        <input type="hidden" name="immolive_id" value="<?php echo get_the_ID() ?>">
                                        <input type="hidden" name="referer" value="<?php echo isset( $_GET['ref'] ) ? substr( sanitize_text_field( $_GET['ref'] ), 0, 8 ) : '' ?>">
                                        <label class="mb-4 block" for="confirm">
                                            <input type="checkbox" name="confirm" id="confirm" x-model="confirm" required>
                                            <span class="inline text-gray-700 text-sm font-bold mb-2">
                                            Ja, ich nehme an diesem Live Webinar über Zoom teil und bin mit den
                                                <a href="<?php echo get_field( 'field_601ec7cd84c47', 'option' ) ?>" target="_blank" class="text-primary-100 underline">
                                                Datenschutzbestimmungen
                                                </a>
                                                der Immobilienredaktion sowie meiner Registrierung auf Zoom (
                                                <a href="https://us02web.zoom.us/privacy" target="_blank" class="text-primary-100 underline">
                                                    Datenschutzrichtlinien
                                                </a>
                                                und
                                                <a href="https://us02web.zoom.us/terms" target="_blank" class="text-primary-100 underline">
                                                Nutzungsbedingungen
                                                </a>
                                                ) einverstanden.
                                            </span>
                                        </label>
                                        <label class="mb-4 block" for="email">
                                            <input type="checkbox" name="email" id="email" x-model="email" required>
                                            <span class="inline text-gray-700 text-sm font-bold">Ja, ich möchte Informationen der Immobilien Redaktion via E-Mail erhalten.</span>
                                        </label>
                                        <label class="block text-gray-700 text-sm font-bold mb-2" for="question">Ihre Frage an unser Poduim</label>
                                        <textarea id="question" name="question" x-model="question" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-4"></textarea>
                                        <button type="submit" class="block w-full bg-primary-100 text-white font-semibold py-3 px-3 focus:outline-none">jetzt anmelden</button>
                                    </form>
                                </div>
                                <div x-show="!isLoggedin">
                                    <h2 class="font-serif font-semibold text-xl mb-4">Um sich zu unseren ImmoLive Webinaren anmelden zu können müssen Sie sich einloggen.</h2>
                                    <p class="mb-4">Sie haben noch keinen Account bei der Immobilien Redaktion? Kein Problen, einfach, schnell und
                                        <a class="text-primary-100 underline"
                                           href="<?php echo add_query_arg( [ 'redirect' => urlencode( get_permalink() ) ], get_field( 'field_6013cf36d4689', 'option' ) ) ?>">
                                            kostenlos registrieren
                                        </a>

                                        .</p>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="col-span-2 xl:col-span-1">

											<?php
											$ref      = $_GET['ref'] ?? 'none';
											$redirect = urlencode( add_query_arg( [ 'ref' => $ref ], get_field( 'field_601e5f56775db', 'option' ) ) )
											?>
                                            <a href="<?php echo add_query_arg( [ 'redirect' => $redirect ], get_field( 'field_601bbffe28967', 'option' ) ) ?>"
                                               class="block bg-primary-100 text-white font-semibold text-center shadow-xl py-3 my-5 text-lg focus:outline-none focus:shadow-outline w-full text-center cursor-pointer">
                                                E-Mail login
                                            </a>
                                        </div>
                                        <div class="col-span-2 xl:col-span-1">
											<?php
											$config = [
												'facebook' => [
													'client_id'     => '831950683917414',
													'client_secret' => 'd6d52d59ce1f1efdbf997b980dffe229',
													'redirect'      => home_url( 'fb-login' ),
												],
											];

											$socialite = new SocialiteManager( $config );
											?>

                                            <a href="<?php echo $socialite->create( 'facebook' )->withState( $redirect )->redirect(); ?>"
                                               class="block bg-white text-primary-100 border border-primary-100 font-semibold text-center shadow-xl py-3 my-5 text-lg focus:outline-none focus:shadow-outline w-full text-center cursor-pointer"
                                            >
                                                Facebook login
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-full h-auto lg:hidden">
                        <img src="<?php the_field( 'field_5fec51051a3f8' ); ?>" class="w-full h-auto z-10">
                    </div>
                </div>
                <svg class="hidden lg:block absolute right-0 inset-y-0 h-full w-48 text-white transform translate-x-1/2 z-40" fill="currentColor" viewBox="0 0 100 100" preserveAspectRatio="none" aria-hidden="true">
                    <polygon points="50,0 100,0 50,100 0,100"/>
                </svg>
            </div>
            <div class="w-1/2 bg-primary-100 h-full hidden lg:block">
                <div class="relative w-full h-full flex justify-center items-center">
                    <div class="bg-white px-5 py-10 shadow-lg z-20" x-data="counter('<?php the_field( 'field_5ed527e9c2279' ) ?>')" x-init="count()">
                        <p class="text-5xl font-semibold text-center">Immo<span class="text-primary-100">Live</span> in
                        </p>
                        <div class="flex justify-center space-x-4">
                            <div class="text-center">
                                <div class="flex items-center justify-center rounded-full bg-primary-100 w-24 h-24 p-3 shadow-lg">
                                    <p x-text="days" class="text-5xl font-semibold text-white -mt-2"></p>
                                </div>
                                <span class="text-xl font-bold">Tage</span>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center rounded-full bg-primary-100 w-24 h-24 p-3 shadow-lg">
                                    <p x-text="hours" class="text-5xl font-semibold text-white -mt-2"></p>
                                </div>
                                <span class="text-xl font-bold">Stunden</span>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center rounded-full bg-primary-100 w-24 h-24 p-3 shadow-lg">
                                    <p x-text="minutes" class="text-5xl font-semibold text-white -mt-2"></p>
                                </div>
                                <span class="text-xl font-bold">Minuten</span>
                            </div>
                            <div class="text-center">
                                <div class="flex items-center justify-center rounded-full bg-primary-100 w-24 h-24 p-3 shadow-lg">
                                    <p x-text="seconds" class="text-5xl font-semibold text-white -mt-2"></p>
                                </div>
                                <span class="text-xl font-bold">Sekunden</span>
                            </div>
                        </div>
                        <p class="py-10 text-center font-semibold">Diese Livestream startet am
                            <br><?php echo \Carbon\Carbon::parse( get_field( 'field_5ed527e9c2279' ) )->format( 'd.m.Y \u\m H:m' ) ?> Uhr.
                        </p>
                    </div>
                    <div class="absolute bottom-0 right-0 w-full h-auto z-30">
                        <img src="<?php the_field( 'field_5fec51051a3f8' ); ?>" class="w-full h-auto">
                    </div>
                </div>
            </div>
        </div>
		<?php
		break;
	endwhile;
endif;
?>


<?php
$query = new \WP_Query( [
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'posts_per_page'      => 10,
	'tag__in'             => 989,
] );
?>

<?php get_template_part( 'banner', 'mega' ) ?>

    <div class="container mx-auto mt-20 px-5 relative">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
			<?php if ( $query->have_posts() ): ?>
				<?php while ( $query->have_posts() ): ?>
					<?php $query->the_post(); ?>
                    <div class="col-span-2 md:col-span-1 relative">
                        <a href="<?php the_permalink(); ?>" class="relative block bg-primary bg-gray-900">
							<?php if ( get_field( 'field_5f96fa1673bac' ) ): ?>
                                <img src="https://img.youtube.com/vi/<?php the_field( 'field_5f96fa1673bac' ) ?>/mqdefault.jpg" class="w-full h-auto max-w-full">
							<?php elseif ( get_field( 'field_5fe2884da38a5' ) ): ?>
                                <img src="<?php echo load_vimeo_image( get_the_ID() ) ?>" class="w-full h-auto">
							<?php endif; ?>
                            <div class="absolute top-0 left-0 w-full h-full"></div>
                            <div class="absolute bottom-0 left-0 hidden lg:block w-full">
								<?php get_template_part( 'snippet', 'heading' ) ?>
                            </div>
                            <div class="absolute top-0 left-0 w-full h-full flex items-center justify-center">
                                <div class="rounded-full bg-white w-24 h-24 m-5 flex items-center justify-center">
                                    <div class="w-12 h-12 animate-ping bg-white rounded-full">
                                        <svg class="w-12 h-12 text-primary-100" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <a href="<?php the_permalink(); ?>" class="block lg:hidden mt-5">
                            <h1 class="text-gray-800 text-lg font-semibold"><?php the_title() ?></h1>
                        </a>
                    </div>
				<?php endwhile; ?>
			<?php endif; ?>
        </div>
        <div class="mt-48">
			<?php \reb_livestream_classes\Pagination::paginate($query->max_num_pages); ?>
        </div>
    </div>
<?php wp_reset_postdata();

get_footer();






