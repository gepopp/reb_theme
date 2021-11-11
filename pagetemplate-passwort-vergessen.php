<?php
/**
 * Template Name: Passwort vergessen
 */
get_header();
the_post();

global $FormSession;
?>

    <div class="container mx-auto mt-20 relative w-96">
        <form class="bg-white shadow-md px-8 pt-6 pb-8 mb-4" method="post" action="<?php echo admin_url('admin-post.php') ?>">
            <h1 class="text-xl font-medium text-gray-700 mb-5"><?php _e('Neues Passwort anfordern', 'ir21') ?></h1>
            <?php $FormSession->flashErrorBag('passwort_reset_error'); ?>
            <?php $FormSession->flashSuccess('passwort_reset'); ?>
            <?php wp_nonce_field('reset_password', 'reset_password') ?>
            <input type="hidden" name="action" value="frontend_reset_password">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    <?php _e('E-Mail Adresse', 'ir21') ?>
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="email"
                       type="email"
                       name="email"
                       placeholder="E-Mail Adresse"
                       required="required">
            </div>
            <p class="text-sm"><?php _e('Bitte geben Sie die E-Mail Adresse ein mit der Sie sich registriert haben. Wir senden Ihnen einen Link zum Zurücksetzen Ihres Passwortes.', 'ir21') ?></p>
            <button class="bg-primary-100 text-white font-medium py-2 px-4 w-full text-center focus:outline-none focus:shadow-outline mt-5"
                    type="submit">
                <?php _e('Link senden', 'ir21') ?>
            </button>
        </form>
    </div>

<?php get_footer();
