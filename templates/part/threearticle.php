<?php
/**
 * @var $query
 */
extract($args);
?>
<div class="container mx-auto mt-20 relative px-5 lg:px-0">
	<div class="grid grid-cols-3 gap-10">
		<?php if ( $query->have_posts() ): ?>
			<?php while ( $query->have_posts() ): ?>
				<?php $query->the_post(); ?>
				<div class="col-span-3 md:col-span-1 relative">
					<div class="col-span-2 md:col-span-1 relative">
						<a href="<?php the_permalink(); ?>" class="relative block bg-primary-100 h-full image-holder">
							<?php the_post_thumbnail( 'article', [ 'class'   => 'w-full h-auto max-w-full',
							                                       'onerror' => "this.style.display='none'",
							] ); ?>
							<?php get_template_part( 'snippet', 'heading' ) ?>
						</a>
					</div>
					<div class="absolute top-0 left-0 w-full p-5 flex justify-between text-white text-sm font-semibold w-full">
						<span><?php the_time( 'd.m.Y' ); ?> | <?php the_author() ?></span>
						<span class="hidden lg:flex"><?php the_category( ', ' ); ?></span>
					</div>
				</div>
			<?php endwhile; ?>
		<?php endif; ?>
	</div>
	<?php wp_reset_postdata(); ?>
</div>
