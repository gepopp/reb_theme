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
        <form class="bg-white shadow-md px-8 pt-6 pb-8 mb-4" method="post" action="<?php echo admin_url('admin-post.php') ?>">
            <?php $FormSession->flashErrorBag('passwort_reset_error'); ?>
            <h1 class="text-xl font-medium text-gray-700 mb-5"><?php _e('Neues Passwort setzen', 'ir21') ?></h1>
            <?php wp_nonce_field('new_password', 'new_password') ?>
            <input type="hidden" name="action" value="new_password">
            <input type="hidden" name="email" value="<?php echo $user->data->user_email ?? '' ?>">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="pw">
                    <?php _e('Neues Passwort', 'ir21') ?>
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="pw"
                       type="password"
                       name="pw"
                       minlength="8"
                       required="required"
                       placeholder="*************************">
            </div>
            <p class="text-sm"><?php _e('Bitte geben Sie Ihr neues Passwort ein, es muss mindestens 8 Zeichen lang sein.', 'ir21') ?></p>
            <button class="bg-primary-100 text-white font-medium py-2 px-4 w-full text-center focus:outline-none focus:shadow-outline mt-5"
                    type="submit">
                <?php _e('Passwort setzten', 'ir21') ?>
            </button>
        </form>
    </div>

<?php get_footer();
