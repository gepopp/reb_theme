<?php
/**
 * Template Name: Aktivierungslink senden
 */
get_header();
the_post();

global $FormSession;
?>
    <div class="container mx-auto relative w-96">
        <form class="bg-black shadow-md px-8 pt-6 pb-8 mb-4 text-white" method="post" action="<?php echo admin_url('admin-post.php') ?>">
            <h1 class="text-2xl mb-4 uppercase font-bold">
                <?php _e('resend account activation link', 'reb_domain') ?></h1>
            <?php $FormSession->flashErrorBag('resend_activation'); ?>
            <?php $FormSession->flashSuccess('resend_activation'); ?>
            <?php wp_nonce_field('resend_activation', 'resend_activation') ?>
            <input type="hidden" name="action" value="resend_activation">
            <div class="reb-input">
                <input id="email"
                       type="email"
                       name="email"
                       placeholder="<?php _e('email address', 'reb_domain') ?>"
                       required="required">
            </div>
            <p class="text-sm"><?php _e('Please enter your registered email address and we send you an email with a link to activate your account.', 'reb_domain') ?></p>
            <button class="bg-logo text-white font-medium py-2 px-4 w-full text-center focus:outline-none focus:shadow-outline mt-5"
                    type="submit">
                <?php _e('send now', 'reb_domain') ?>
            </button>
        </form>
    </div>
<?php get_footer();
