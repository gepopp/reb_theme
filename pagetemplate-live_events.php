<?php

use function reb_livestream_theme\load_vimeo_image;

/**
 * Template Name: Landing Live Events
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
	'order'          => 'DESC',
	'meta_key'       => 'il_datum',
	'orderby'        => 'meta_value_num',
] );
$count = $query->post_count;

$runner = 1;

if ( $query->have_posts() ):
	while ( $query->have_posts() ):
		$query->the_post();

		if ( (int) date( 'Gi' ) > 1601 && date( 'Ymd' ) == get_field( 'field_5ed527e9c2279', get_the_ID(), false ) && $runner == 1 ) {
			$runner ++;
			continue;
		}

		get_template_part( 'snippet', 'event' );

		$speakers = get_field( 'field_6007f8b5a20f0' );

		if ( is_user_logged_in() ) {


			$wrapper          = new \reb_livestream_classes\ZoomAPIWrapper();
			$zoom_registrants = $wrapper->doRequest( 'GET', '/webinars/' . get_field( 'field_60127a6c90f6b' ) . '/registrants' );

			$registrants = get_field( 'field_601451bb66bc3' );

			$emails = [];
			if ( $registrants ) {
				foreach ( $registrants as $registrant ) {
					$emails[] = $registrant['user_email'];
				}
			}

			if ( ! empty( $zoom_registrant ) ) {

				foreach ( $zoom_registrants['registrants'] as $zoom_registrant ) {


					if ( ! in_array( $zoom_registrant['email'], $emails ) ) {
						add_row( 'field_601451bb66bc3', [
							'user_name'            => $zoom_registrant['first_name'] . ' ' . $zoom_registrant['last_name'],
							'user_email'           => $zoom_registrant['email'],
							'frage_ans_podium'     => $zoom_registrant['comments'],
							'zoom_registrant_id'   => $zoom_registrant['id'],
							'zoom_teilnehmer_link' => $zoom_registrant['join_url'],
						], get_the_ID() );
					}
				}
			}
		}
		?>
        <div class="container mx-auto border-15 border-white bg-primary-100 px-5 lg:px-12 py-10">
            <div class="flex justify-end md:justify-between w-full py-5 text-xl lg:text-3xl text-white font-light leading-none">
                <p class="w-full lg:w-1/3 hidden md:block"><?php _e( 'Das größte Online-Event der österreichischen Immobilienwirtschaft', 'ir21' ) ?></p>
                <div class="font-normal text-right flex-shrink-0">
                    <p><?php
						echo \Carbon\Carbon::parse( get_field( 'field_5ed527e9c2279' ), 'Europe/Vienna' )->format( 'd.m.Y H:i' );
						?></p>
                    <p><?php _e( 'Zoom Webinar', 'ir21' ) ?></p>
                </div>
            </div>

            <div class="flex flex-col items-center">
                <h1 class="text-3xl lg:text-5xl text-white text-center font-extrabold max-w-full overflow-hidden leading-normal break-words"><?php the_title() ?></h1>
                <div class="text-lg text-white mb-10 lg:w-2/3 text-center"><?php the_content(); ?></div>
            </div>

            <div>
                <div class="" x-data="counter('<?php the_field( 'field_5ed527e9c2279' ) ?>')" x-init="count()">
					<?php get_template_part( 'immolive', 'counter-v2' ) ?>
                    <div class="flex justify-center my-20">

						<?php
						$subscribed  = false;
						$user        = wp_get_current_user();
						$registrants = get_field( 'field_601451bb66bc3' );

						if ( $registrants ) {
							foreach ( $registrants as $registrant ) {
								if ( $registrant['user_email'] == $user->user_email ) {
									$subscribed = true;
								}
							}
						}
						if ( ! $subscribed || ! is_user_logged_in() ):
							?>
                            <a class="py-2 px-10 text-primary-100 bg-white shadow-xl hover:shadow-none text-xl lg:text-3xl font-medium cursor-pointer"
                               @click="$dispatch('register-immolive', { id: <?php the_ID(); ?>, user: <?php echo is_user_logged_in() ? 'true' : 'false' ?> })">
								<?php _e( 'Jetzt anmelden', 'ir21' ) ?>
                            </a>
						<?php else: ?>
                            <p class="py-2 px-10 text-white border border-white shadow-xl hover:shadow-none text-xl lg:text-3xl font-medium cursor-pointer">
								<?php _e( 'Sie sind zu dieser Veranstaltung angemeldet.', 'ir21' ) ?>
                            </p>
						<?php endif; ?>


                    </div>
                </div>
            </div>


			<?php if ( $speakers ): ?>
				<?php if ( count( $speakers ) == 1 ): ?>
					<?php get_template_part( 'snippet', 'horizontalspeaker', [ 'speaker' => array_shift( $speakers ) ] ); ?>

				<?php endif; ?>

				<?php if ( count( $speakers ) == 2 ): ?>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 my-10">
                        <div>
							<?php get_template_part( 'snippet', 'horizontalspeaker', [ 'speaker' => array_shift( $speakers ) ] ); ?>

                        </div>
                        <div>
							<?php get_template_part( 'snippet', 'horizontalspeaker', [ 'speaker' => array_shift( $speakers ) ] ); ?>

                        </div>
                    </div>
				<?php endif; ?>

				<?php if ( count( $speakers ) > 2 ): ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-<?php echo min( 4, count( $speakers ) ) ?> gap-10">
						<?php
						while ( $speaker = array_shift( $speakers ) ) {
							get_template_part( 'snippet', 'verticalspeaker', [ 'speaker' => $speaker ] );
						}
						?>
                    </div>
				<?php endif; ?>
			<?php endif; ?>
        </div>
		<?php
		break;
	endwhile;
endif;
?>


<?php get_template_part( 'banner', 'mega' ) ?>


<?php
$query = new \WP_Query( [
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'posts_per_page'      => 6,
	'cat'                 => 991,
] );
$pages = (int) $query->max_num_pages;


$posts = [];

if ( $query->have_posts() ):
	$runner = 1;
	while ( $query->have_posts() ):
		$query->the_post();

		if ( get_field( 'field_5f96fa1673bac' ) ):
			$url = "https://img.youtube.com/vi/" . get_field( 'field_5f96fa1673bac' ) . "/mqdefault.jpg";
        elseif ( get_field( 'field_5fe2884da38a5' ) ):

			$url = get_the_post_thumbnail_url();
		else:
			$url = false;
		endif;

		$posts[] = [
			'ID'        => get_the_ID(),
			'permalink' => get_the_permalink(),
			'title'     => get_the_title(),
			'img'       => $url,
		];
		$runner ++;
	endwhile;
endif;
?>
<?php wp_reset_postdata(); ?>
    <div class="container mx-auto mt-20 px-5 lg:px-0">
        <a href="#" class="text-xl font-bold">ImmoLive Diskussionen</a>
        <div x-data="slider(<?php echo str_replace( '"', "'", json_encode( $posts ) ) ?>, 991, <?php echo $query->max_num_pages ?> )"
             x-init="
                load();
                $watch('active', (value) => {
                    if(value + 1 == rows.length) load();
                })
                "
             class="relative">

            <div class="snap overflow-auto relative flex-no-wrap flex transition-all"
                 x-ref="slider"
                 x-on:scroll.debounce="active = Math.round($event.target.scrollLeft / ($event.target.scrollWidth / rows.length))">
                <template x-for="row in rows">
                    <div class="w-full flex-shrink-0 text-white flex items-center justify-center">
                        <div class="grid grid-cols-6 gap-4">
                            <template x-for="post in row">
                                <div class="col-span-3 lg:col-span-1">
                                    <div class="relative">

                                        <div class="w-full bg-primary-100" style="padding-top: 56%">
                                            <img :src="post.img" class="w-full h-auto" style="margin-top: -56%"/>
                                        </div>

                                        <a :href="post.permalink" class="absolute top-0 left-0 w-full h-full bg-gray-900 bg-opacity-25 flex justify-center items-center">
                                            <div class="w-4 h-4 bg-white rounded-full">
                                                <svg class="w-4 h-4 text-primary-100" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                        </a>

                                    </div>
                                    <p class="mt-5 font-semibold text-xs text-gray-800 h-24">
                                        <a :href="post.permalink" x-text="post.title"></a>
                                    </p>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
            <div class="flex items-center justify-between flex-1 absolute top-0 w-full h-full" style="pointer-events: none">
                <button class="outline-none focus:outline-none rounded-full mx-4 text-white w-8"
                        :class="{'cursor-not-allowed' : loading || active <= pages - 1  }"
                        style="pointer-events: auto"
                        x-on:click="prev($refs);">
                    <div class="relative w-10 h-10 flex items-center justify-center">
                        <div class="absolute animate-ping w-6 h-6 rounded-full bg-gray-600 bg-opacity-50 center"></div>
                        <div class="w-10 h-10 p-2 rounded-full bg-gray-900 flex items-center justify-center z-20">
                            <svg x-show="active > 0 && !loading" class="z-50 w-6 h-6 text-white inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <svg x-show="active <= pages - 1 && !loading" class="z-50 w-6 h-6 text-white inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <svg x-show="loading" class="z-50 w-6 h-6 text-warning inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>

                </button>

				<?php if ( get_field( 'field_5f9aefd116e2e', 991 ) ): ?>
                    <div class="bg-gray-900 bg-opacity-75 rounded-full w-24 h-24 p-5 flex flex-col items-center justify-center shadow-lg">
                        <a href="<?php echo get_field( 'field_5f9aeff4efa16', 991 ) ?>" class="text-center" style="pointer-events: auto">
                            <p class="text-white" style="font-size: .5rem"><?php _e( 'powered by', 'ir21' ) ?></p>
                            <img src="<?php echo get_field( 'field_5f9aefd116e2e', 991 ) ?>" class="w-20 h-auto">
                        </a>
                    </div>
				<?php endif; ?>

                <button class="outline-none focus:outline-none rounded-full mx-4 text-white w-8"
                        :class="{'cursor-not-allowed' : loading || active >= pages }"
                        style="pointer-events: auto"
                        x-on:click="next($refs);">
                    <div class="relative w-10 h-10 flex items-center justify-center">
                        <div class="absolute animate-ping w-6 h-6 rounded-full bg-gray-600 bg-opacity-50 center"></div>
                        <div class="w-10 h-10 p-2 rounded-full bg-gray-900 flex items-center justify-center z-20">
                            <svg x-show="active < pages && !loading" class="w-8 h-8 text-white inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <svg x-show="active >= pages - 1 && !loading" class="w-8 h-8 text-white inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <svg x-show="loading" class="w-8 h-8 text-warning inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </button>
            </div>
        </div>
    </div>
<?php
$cats      = get_field( 'field_60733fe611fac', 'option' );

foreach ( $cats as $cat ):

	$cat = get_category( $cat );
	$query = new WP_Query( [
		'post_type'           => 'post',
		'post_status'         => 'publish',
		'ignore_sticky_posts' => true,
		'posts_per_page'      => 6,
		'category__in'        => [ $cat->term_id ],
	] );
	?>
	<?php

	$pages = (int) $query->max_num_pages;
	$posts = [];

	if ( $query->have_posts() ):
		$runner = 1;
		while ( $query->have_posts() ):
			$query->the_post();

			if ( get_field( 'field_5f96fa1673bac' ) ):
				$url = "https://img.youtube.com/vi/" . get_field( 'field_5f96fa1673bac' ) . "/mqdefault.jpg";
            elseif ( get_field( 'field_5fe2884da38a5' ) ):

				$url = load_vimeo_image( get_the_ID() );
			else:
				$url = false;
			endif;

			$posts[] = [
				'ID'        => get_the_ID(),
				'permalink' => get_the_permalink(),
				'title'     => get_the_title(),
				'img'       => $url,
			];
			$runner ++;
		endwhile;
	endif;
	?>
	<?php wp_reset_postdata(); ?>


    <div class="container mx-auto mt-20 px-5 lg:px-0">
        <a href="#" class="text-xl font-bold"><?php echo $cat->name ?></a>
        <div x-data="slider(<?php echo str_replace( '"', "'", json_encode( $posts ) ) ?>, <?php echo $cat->term_id ?>, <?php echo $query->max_num_pages ?> )"
             x-init="
                load();
                $watch('active', (value) => {
                    if(value + 1 == rows.length) load();
                })
                "
             class="relative">
            <div class="snap overflow-auto relative flex-no-wrap flex transition-all"
                 x-ref="slider"
                 x-on:scroll.debounce="active = Math.round($event.target.scrollLeft / ($event.target.scrollWidth / rows.length))">
                <template x-for="row in rows">
                    <div class="w-full flex-shrink-0 text-white flex items-center justify-center">
                        <div class="grid grid-cols-6 gap-4">
                            <template x-for="post in row">
                                <div class="col-span-3 lg:col-span-1">
                                    <div class="relative">
                                        <div class="w-full bg-primary-100" style="padding-top: 56%">
                                            <a :href="post.permalink">
                                                <img :src="post.img" class="w-full h-auto" style="margin-top: -56%"/>
                                            </a>
                                        </div>
                                    </div>
                                    <p class="mt-5 font-semibold text-xs text-gray-800 h-24">
                                        <a :href="post.permalink" x-text="post.title"></a>
                                    </p>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
            <div class="flex items-center justify-between flex-1 absolute top-0 w-full h-full" style="pointer-events: none">
                <button class="outline-none focus:outline-none rounded-full mx-4 text-white w-8"
                        :class="{'cursor-not-allowed' : loading || active <= 0  }"
                        style="pointer-events: auto"
                        x-on:click="prev($refs);">
                    <div class="relative w-10 h-10 flex items-center justify-center">
                        <div class="absolute animate-ping w-6 h-6 rounded-full bg-gray-600 bg-opacity-50 center"></div>
                        <div class="w-10 h-10 p-2 rounded-full bg-gray-900 flex items-center justify-center z-20">
                            <svg x-show="active > 0 && !loading" class="z-50 w-6 h-6 text-white inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <svg x-show="active <= 0 && !loading" class="z-50 w-6 h-6 text-white inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <svg x-show="loading" class="z-50 w-6 h-6 text-warning inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </button>

				<?php if ( get_field( 'field_5f9aefd116e2e', 991 ) ): ?>
                    <div class="bg-gray-900 bg-opacity-75 rounded-full w-24 h-24 p-5 flex flex-col items-center justify-center shadow-lg">
                        <a href="<?php echo get_field( 'field_5f9aeff4efa16', 991 ) ?>" class="text-center" style="pointer-events: auto">
                            <p class="text-white" style="font-size: .5rem"><?php _e( 'powered by', 'ir21' ) ?></p>
                            <img src="<?php echo get_field( 'field_5f9aefd116e2e', 991 ) ?>" class="w-20 h-auto">
                        </a>
                    </div>
				<?php endif; ?>

                <button class="outline-none focus:outline-none rounded-full mx-4 text-white w-8"
                        :class="{'cursor-not-allowed' : loading || active >= pages - 1 }"
                        style="pointer-events: auto"
                        x-on:click="next($refs);">
                    <div class="relative w-10 h-10 flex items-center justify-center">
                        <div class="absolute animate-ping w-6 h-6 rounded-full bg-gray-600 bg-opacity-50 center"></div>
                        <div class="w-10 h-10 p-2 rounded-full bg-gray-900 flex items-center justify-center z-20">
                            <svg x-show="active < pages && !loading" class="w-8 h-8 text-white inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L14.586 10l-4.293-4.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                <path fill-rule="evenodd" d="M4.293 15.707a1 1 0 010-1.414L8.586 10 4.293 5.707a1 1 0 011.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <svg x-show="active >= pages - 1 && !loading" class="w-8 h-8 text-white inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <svg x-show="loading" class="w-8 h-8 text-warning inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </button>
            </div>
        </div>
    </div>
<?php
endforeach;
wp_reset_postdata();
get_footer();






