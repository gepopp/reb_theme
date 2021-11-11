<?php if (!empty(get_field('field_5ded37c474589', 'user_' . get_the_author_meta('ID'))['sizes']['xs']) && !empty(get_the_author_meta('description'))
): ?>

    <div class="conatainer mx-auto mt-20 flex justify-center items-center">
        <div class="flex justify-center items-center">
            <img src="<?php echo get_field('field_5ded37c474589', 'user_' . get_the_author_meta('ID'))['sizes']['author_small'] ?>" class="rounded-full w-12 h-12">
            <p class="ml-5 text-xl underline"><?php echo get_the_author_posts_link(get_the_ID()) ?></p>
        </div>
    </div>

<?php else: ?>


    <div class="conatainer mx-auto mt-20 flex justify-center items-center">
        <div class="flex justify-center items-center">
            <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/icon.svg" class="rounded-full w-12 h-12">
            <p class="ml-5 text-xl">
                <?php echo get_the_author_meta('display_name') ?>
            </p>
        </div>
    </div>

<?php endif; ?>

