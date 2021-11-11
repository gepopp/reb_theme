<?php


$query = new \WP_Query( [
	'post_type'           => 'post',
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
	'posts_per_page'      => 2,
	'category__in'        => [ 17 ],
] );
?>
    <div class="px-5 xl:px-0">
        <div class="container mx-auto mt-20">
            <div class="grid grid-cols-2 gap-10">
				<?php if ( $query->have_posts() ): ?>
					<?php while ( $query->have_posts() ): ?>
						<?php $query->the_post(); ?>
                        <div class="col-span-2 md:col-span-1 relative">
                            <a href="<?php the_permalink(); ?>" class="relative block bg-primary bg-gray-900">
                                <div class="relative w-full pt-16by9 bg-white">
                                    <div class="absolute top-0 left-0 w-full h-full">
	                                    <?php if (get_field('field_5f96fa1673bac')): ?>
                                            <img src="https://img.youtube.com/vi/<?php the_field('field_5f96fa1673bac') ?>/mqdefault.jpg" class="w-full h-auto max-w-full">
	                                    <?php elseif (get_field('field_5fe2884da38a5')): ?>
                                            <img src="<?php the_post_thumbnail_url('featured'); ?>" class="w-full h-auto">
	                                    <?php endif; ?>
                                        <div class="absolute top-0 left-0 w-full h-full bg-gray-900 bg-opacity-25"></div>
                                        <div class="absolute bottom-0 left-0 w-full hidden lg:block">
		                                    <?php get_template_part('snippet', 'heading') ?>
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
        </div>
    </div>
<?php wp_reset_postdata(); ?>