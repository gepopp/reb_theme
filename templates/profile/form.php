<?php

global $FormSession;

$user = wp_get_current_user();

$gender = get_field('field_5fb6bc5f82e62', 'user_' . get_current_user_id());

?>
<div class="col-span-3 lg:col-span-1">

    <form class="bg-black text-white shadow-md px-8 pt-6 pb-8 mb-4 h-full" method="post" action="<?php echo admin_url('admin-post.php') ?>">
        <h1 class="text-3xl font-bold mb-5 uppercase"><?php _e('Your Data', 'reb_domain') ?></h1>
        <?php $FormSession->flashErrorBag('profile_error'); ?>
        <?php $FormSession->flashSuccess('profile_updated'); ?>


        <?php wp_nonce_field('frontend_register', 'frontend_register') ?>
        <input type="hidden" name="action" value="update_profile">
        <div class="reb-input">
            <select name="register_gender" id="register_gender"
                    required="required"
                    name="profile_gender">
                <option value=""><?php _e('Please choose ...', 're_domain') ?></option>
                <option value="f" <?php echo $gender == 'f' ? 'selected="selected"' : '' ?>><?php _e('Mrs.', 'reb_domain') ?></option>
                <option value="m" <?php echo $gender != 'f' ? 'selected="selected"' : '' ?>><?php _e('Mr.', 'reb_domain') ?></option>
            </select>
        </div>
        <div class="grid grid-cols-2 gap-4 mb-4">
            <div class="reb-input">
                <input id="first_name"
                       type="text"
                       name="fist_name"
                       placeholder="<?php _e('firstname', 'reb_domain') ?>"
                       value="<?php echo $user->first_name ?>"
                >
            </div>
            <div class="reb-input">
                <input id="last_name"
                       type="text"
                       name="last_name"
                       placeholder="<?php _e('lastname', 'reb_domain') ?>"
                       value="<?php echo $user->last_name ?>"
                >
            </div>
        </div>
        <div class="flex items-center justify-between">
            <button class="w-full bg-logo text-center text-white font-bold py-2 px-4 focus:outline-none focus:shadow-outline g-recaptcha flex justify-center items-center"
                                        type="submit">
                <?php _e('save', 'reb_domain') ?>
            </button>
        </div>
    </form>
</div>
