<?php
get_header();
the_post();
?>

<div class="container relative">

    <div class="w-ful py-36 text-center bg-black mb-5">
        <p class="text-white text-center text-8xl"><?php the_title() ?></p>
    </div>
    <h1 class="text-3xl font-normal	mb-5"><?php the_title() ?></h1>
    <div class="content">
        <?php the_content(); ?>
    </div>

</div>

<?php get_footer();




