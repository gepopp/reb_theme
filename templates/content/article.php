<?php
$user = wp_get_current_user();
$post = get_the_ID();

$cat = wp_get_post_categories( get_the_ID() );
$cat = array_shift( $cat );
$cat = get_category( $cat );

?>

<div class="px-5 lg:px-5"
     x-data="readingLog(<?php echo $user->ID ?? false ?>, <?php echo $post ?>)"
     x-init="getmeasurements();"
     @scroll.window.debounce.1s="amountscrolled()"
     @resize.window="getmeasurements()"
     ref="watched"
>
	<?php get_template_part( 'article', 'header' ) ?>

    <div class="container mx-auto">
        <div class="grid grid-cols-5 gap-4">
            <div>
				<?php get_template_part( 'article', 'left' ) ?>
            </div>
            <div class="content col-span-5 lg:col-span-3" id="article-content">

                <h1 class="text-2xl lg:text-5xl font-serif leading-none text-gray-900 mb-5">
					<?php the_title() ?>
                </h1>
                <h3 class="mb-10">
                    <strong>
						<?php echo get_the_excerpt(); ?>
                    </strong>
                </h3>
				<?php if ( get_post_type() == 'aktuelle_presse'  ): ?>

                    <div class="block lg:hidden grid xs:grid-cols-1 md:grid-cols-2 mb-10">
                        <div class="flex flex-col justify-between" style="background-color: <?php the_field('field_613b5990f3543', 'option'); ?>">
                            <div>
                                <p class="px-5 pt-5 font-serif text-2xl text-white">Aktuelles</p>
                                <p class="px-5 pb-5 text-white text-sm -mt-3">powered by</p>
                            </div>
                            <div class="p-5 text-white hidden md:block">
                                <a href="<?php echo get_field('field_613b5a844db76', 'option') ?>">
                                    <span class="text-white underline"><?php echo wp_count_posts('aktuelle_presse')->publish ?> Artikel</span>
                                </a>
                            </div>
                        </div>
                        <div class="bg-white">
                            <a href="<?php the_field('field_613b5a844db76', 'option') ?>" class="text-center">
                                <img src="<?php the_field( 'field_613b59adf3545', 'option') ?>" class="w-full h-auto p-5">
                            </a>
                        </div>
                    </div>

	            <?php elseif ( get_post_type() == 'zur_person'  ): ?>

                    <div class="block lg:hidden grid xs:grid-cols-1 md:grid-cols-2 mb-10">
                        <div class="flex flex-col justify-between" style="background-color: <?php the_field('field_613b878f77b81', 'option'); ?>">
                            <div>
                                <p class="px-5 pt-5 font-serif text-2xl text-white">Menschen</p>
                                <p class="px-5 pb-5 text-white text-sm -mt-3">powered by</p>
                            </div>
                            <div class="p-5 text-white hidden md:block">
                                <a href="<?php echo get_field('field_613b5a844db76', 'option') ?>">
                                    <span class="text-white underline"><?php echo wp_count_posts('zur_person')->publish ?> Artikel</span>
                                </a>
                            </div>
                        </div>
                        <div class="bg-white">
                            <a href="<?php the_field('field_613b5a844db76', 'option') ?>" class="text-center">
                                <img src="<?php the_field( 'field_613b59adf3545', 'option') ?>" class="w-full h-auto p-5">
                            </a>
                        </div>
                    </div>


				<?php else: ?>

					<?php if ( get_field( 'field_60da235237ec4', $cat ) ): ?>
                        <div class="block lg:hidden grid xs:grid-cols-1 md:grid-cols-2 mb-10">
                            <div class="flex flex-col justify-between" style="background-color: <?php the_field( 'field_5c63ff4b7a5fb', $cat ); ?>">
                                <div>
                                    <p class="px-5 pt-5 font-serif text-2xl text-white"><?php echo $cat->name ?? '' ?></p>
                                    <p class="px-5 pb-5 text-white text-sm -mt-3">powered by</p>
                                </div>
                                <div class="p-5 text-white hidden md:block">
                                    <a href="<?php echo get_category_link( $cat ) ?>">
                                        <span class="text-white underline"><?php echo $cat->count ?? '' ?><?php _e( 'Artikel', 'ir21' ) ?></span>
                                    </a>
                                </div>
                            </div>
                            <div class="bg-white">
                                <a href="<?php echo get_field( 'field_5f9aeff4efa16', $cat ) ?>" class="text-center">
                                    <img src="<?php the_field( 'field_60da235237ec4', $cat ); ?>" class="w-full h-auto p-5">
                                </a>
                            </div>
                        </div>
					<?php endif; ?>
				<?php endif; ?>

				<?php the_content(); ?>

                <div class="mt-10">
					<?php
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
					?>
                </div>


            </div>
            <div>
				<?php get_template_part( 'article', 'right' ) ?>
            </div>
        </div>
    </div>
</div>

<?php get_template_part( 'article', 'readmore' ) ?>

<div class="lg:hidden sticky bottom-0"
     x-data="{ scroll: 0, max : 0 }"
     x-init="
        contentContainer = document.getElementById('article-content');
        max = contentContainer.offsetTop + contentContainer.offsetHeight - 200;
        maxScrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        window.addEventListener('resize', () => {
            maxScrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        });
        window.addEventListener('scroll', function (event) {

            contentContainer = document.getElementById('article-content');
            max = contentContainer.offsetTop + contentContainer.offsetHeight - 200;
            scroll = this.scrollY;

        });

     ">
    <div x-show.transition.fade.500ms="scroll > 200 && scroll < max">
		<?php get_template_part( 'article', 'iconbar' ) ?>
    </div>
</div>