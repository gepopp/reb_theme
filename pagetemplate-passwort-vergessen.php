<?php
/**
 * Template Name: Passwort vergessen
 */
get_header();
the_post();

global $FormSession;
?>

    <div class="container mx-auto mt-20 relative w-96">
        <form class="bg-black text-white shadow-md px-8 pt-6 pb-8 mb-4" method="post" action="<?php echo admin_url('admin-post.php') ?>">
            <h1 class="text-2xl mb-4 uppercase font-bold">
                <?php _e('Create a new password', 'reb_domain') ?>
            </h1>
            <?php $FormSession->flashErrorBag('passwort_reset_error'); ?>
            <?php $FormSession->flashSuccess('passwort_reset'); ?>
            <?php wp_nonce_field('reset_password', 'reset_password') ?>
            <input type="hidden" name="action" value="frontend_reset_password">
            <div class="reb-input">
                <input id="email"
                       type="email"
                       name="email"
                       placeholder="<?php _e('email address', 'reb_domain') ?>"
                       required="required">
            </div>
            <p class="text-sm mb-4">
                <?php _e('Please enter your registered email address and you will receive a message with a link to reset your password.', 'ir21') ?>
            </p>
            <button class="w-full bg-logo text-center text-white font-bold py-2 px-4 focus:outline-none focus:shadow-outline"
                    type="submit">
                <?php _e('send link', 'reb_domain') ?>
            </button>
        </form>
    </div>

<?php get_footer();
