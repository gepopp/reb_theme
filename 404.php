<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link    https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Freeshifter
 */

get_header(); ?>

    <section class="container mx-auto relative z-10 my-64">
        <h1 class="text-2xl font-serif"><?php _e('Hoppla, diesen Inhalt konnten wir nicht finden...', 'ir21') ?></h1>
        <p><?php echo sprintf(__('Hie kommen Sie zurück zur <a href="%s" class="underline">Startseite</a>.', 'ir21'), home_url()) ?></p>
    </section>

<?php
get_footer();
