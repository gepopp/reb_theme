<?php
global $FormSession;
?>

<form class="bg-black text-white" method="post" action="<?php echo admin_url( 'admin-post.php' ) ?>">
	<?php $FormSession->flashSuccess( 'token_success' ) ?>
	<?php $FormSession->flashErrorBag( 'login_errror' ) ?>
	<?php wp_nonce_field( 'frontend_login', 'frontend_login' ) ?>
    <input type="hidden" name="action" value="frontend_login">
    <input type="hidden" name="redirect" value="<?php echo sanitize_text_field( $_GET['redirect'] ?? '' ) ?>">
    <div class="reb-input">
        <input id="email"
               type="email"
               name="email"
               placeholder="<?php _e( 'email address', 'rep_domain' ) ?>"
               x-model="email"
               @keyup.debounce.500ms="ValidateEmail()"
               autocomplete="email">
        <p x-show="error.email" x-text="error.email" class="text-warning text-xs"></p>
    </div>
    <div class="reb-input">
        <input id="password"
               type="password"
               name="password"
               x-model="password"
               @keyup.debounce.500ms="checkCompleted()"
               placeholder="<?php _e( 'password', 'reb_domain' ) ?>"
               autocomplete="current-password">
        <p x-show="error.password" x-text="error.password" class="text-warning text-xs"></p>
        <a class="inline align-baseline text-xs underline" href="<?php the_field( 'field_601e59c9336d7', 'option' ) ?>">
		    <?php _e( 'forgotten?', 'rep_domain' ) ?>
        </a>
    </div>


    <div class="md:flex md:items-center -mt-4 mb-4">
        <label class="block font-bold flex items-center relative" x-data="{ check : false }" @click="check = !check">
            <input class="hidden" type="checkbox" name="remember" :value="check">
            <div class="w-4 h-4 rounded-full bg-white mr-2"></div>
            <svg class="w-4 h-4 absolute text-logo" x-show="check" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="text-sm"><?php _e( 'remember me.', 'rep_domain' ) ?></span>
        </label>
    </div>
    <div class="flex items-center justify-between">
        <button class="w-full bg-logo text-center text-white font-bold py-2 px-4 focus:outline-none focus:shadow-outline"
                :class="{' cursor-not-allowed ': !completed }"
                type="submit"
                :disabled="!completed">
			<?php _e( 'login', 'rep_domain' ) ?>
        </button>
    </div>
	<?php do_action( 'login_form' ); ?>
</form>

<div class="flex justify-between text-xs font-bold">
    <p class="font-medium"><?php _e( 'no account yet?', 'rep_domain' ) ?></p>
    <p>
        <a href="<?php echo get_field( 'field_601bc00528968', 'option' ) ?>" class="underline"><?php _e( 'register', 'rep_domain' ) ?></a>
    </p>
</div>
