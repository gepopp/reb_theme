<?php
/**
 * Template Name: User Profil
 */
get_header();
the_post();

global $wpdb;
?>
    <div class="container mx-auto mt-48 relative px-5 lg:px-0">
        <div class="grid grid-cols-3 gap-10">
            <?php get_template_part('profile', 'form') ?>
            <?php get_template_part('profile', 'useremail') ?>
            <?php get_template_part('profile', 'image') ?>
        </div>
    </div>

    <?php get_template_part('log', 'reading' ) ?>
<?php
get_footer();