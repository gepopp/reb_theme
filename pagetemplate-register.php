<?php
/**
 * Template Name: resgistration page
 */
get_header();

if (isset($_GET['token'])) {
    \reb_livestream_theme\activate_user(sanitize_text_field($_GET['token']));;
}

global $FormSession;

?>
    <div class="container mx-auto relative px-5 md:px-0 flex justify-center">
        <div class="h-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <?php get_template_part('register', 'form') ?>
                <div>
                    <h1 class="text-3xl font-bold mb-5">
                        <?php the_title(); ?>
                    </h1>
                    <div class="content">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php
get_footer();