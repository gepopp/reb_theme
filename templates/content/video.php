<?php
$user = wp_get_current_user();
$post_id = get_the_ID();
?>
<?php get_template_part('video', 'head') ?>

<div class="container mx-auto text-white" x-data="{ showMore: false }">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-10">
        <div class="content col-span-4 lg:col-span-3" id="article-content">
            <h1 class="text-xl lg:text-3xl font-serif leading-none text-white">
				<?php echo get_the_title() ?>
            </h1>
            <div class="hidden sm:block">
				<?php get_template_part( 'video', 'meta', [ 'mode' => 'dark' ] ) ?>
            </div>
            <div :class="showMore ? '' : 'line-clamp-3'">
				<?php the_content(); ?>
            </div>
            <div class="flex justify-end w-full" @click="showMore = true" x-show.transition="!showMore">
                <p class="uppercase cursor-pointer flex space-x-3">
                    <span>mehr</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </p>
            </div>
            <div class="flex justify-end w-full" @click="showMore = false" x-show.transition="showMore">
                <p class="uppercase cursor-pointer flex space-x-3">
                    <span>weniger</span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                    </svg>
                </p>
            </div>
            <hr class="py-3">
            <div class="">
				<?php
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
				?>
            </div>
        </div>
        <div class="content col-span-4 lg:col-span-1">
			<?php get_template_part( 'video', 'chapters' ) ?>
			<?php get_template_part( 'video', 'speaker' ) ?>
        </div>
    </div>
</div>
