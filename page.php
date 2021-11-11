<?php
get_header();
the_post();
?>

<div class="container mx-auto mt-20 relative px-5 lg:px-0">

    <h1 class="text-5xl"><?php the_title() ?></h1>
    <div class="content">

        <?php the_content(); ?>

    </div>

</div>







<?php get_footer() ?>




