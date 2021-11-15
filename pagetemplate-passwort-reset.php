<?php
/**
 * Template Name: Passwort reset
 */
get_header();
the_post();

$user = false;

global $FormSession;

if (isset($_GET['token'])) {
    global $wpdb;
    $user = get_user_by('email', $wpdb->get_var('SELECT email FROM wp_user_activation_token WHERE token = "' . sanitize_text_field($_GET['token']) . '"'));
    if ($user) {
        $wpdb->delete('wp_user_activation_token', ['email' => $user->data->user_email]);
    } else {
        $FormSession->addToErrorBag('passwort_reset_error', 'token_expired');
    }
}
?>

    <div class="container mx-auto mt-20 relative w-96">
        <form class="bg-black text-white shadow-md px-8 pt-6 pb-8 mb-4" method="post" action="<?php echo admin_url('admin-post.php') ?>">
            <?php $FormSession->flashErrorBag('passwort_reset_error'); ?>
            <h1 class="text-2xl mb-4 uppercase font-bold">
                <?php _e('create a new password', 'reb_domain') ?>
            </h1>
            <?php wp_nonce_field('new_password', 'new_password') ?>
            <input type="hidden" name="action" value="new_password">
            <input type="hidden" name="email" value="<?php echo $user->data->user_email ?? '' ?>">
            <div class="reb-input">
                <input id="pw"
                       type="password"
                       name="pw"
                       minlength="8"
                       required="required"
                       placeholder="<?php _e('new password', 'reb_domain') ?>">
            </div>
            <p class="text-sm"><?php _e('Please enter at least 8 characters', 'reb_domain') ?></p>
            <button class="bg-logo text-white font-medium py-2 px-4 w-full text-center focus:outline-none focus:shadow-outline mt-5"
                    type="submit">
                <?php _e('reset password', 'ir21') ?>
            </button>
        </form>
    </div>

<?php get_footer();
