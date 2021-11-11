<?php
$query = new WP_Query( [
	'post_status'    => 'publish',
	'post_type'      => 'immobilien_projekt',
	'posts_per_page' => 8,
] );

if ( $query->have_posts() ):
	?>
    <div class="container mx-auto p-5 mt-20">

        <div class="text-center mb-10">
            <h1 class="inline text-cent font-serif text-3xl font-semibold"
                style="background: linear-gradient(0deg, <?php the_field('field_613b8693e9b81', 'option'); ?> 0%, <?php the_field('field_613b8693e9b81', 'option'); ?> 50%, transparent 50%, transparent 100%);">Immobilien Projekte</h1>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

			<?php while ( $query->have_posts() ): ?>
				<?php $query->the_post(); ?>

                <div class="relative">
                    <a href="<?php the_permalink(); ?>" class="block bg-primary-100 h-full image-holder">
						<?php the_post_thumbnail( 'article', [
							'class' => 'w-full h-auto max-w-full',
						] ); ?>
						<?php get_template_part( 'snippet', 'heading', [ 'size' => 'small' ] ) ?>
                    </a>
                    <div class="absolute top-0 left-0 w-full p-5 flex justify-between text-white text-sm font-semibold w-full">
                        <span><?php the_time( 'd.m.Y' ); ?></span>
                        <span class="hidden lg:flex"><?php the_category( ', ' ); ?></span>
                    </div>
                </div>
			<?php endwhile; ?>
        </div>
    </div>
<?php
endif;