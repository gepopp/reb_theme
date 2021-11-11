<?php
global $FormSession;
?>

<form class="mb-4" method="post" action="<?php echo admin_url( 'admin-post.php' ) ?>">
	<?php $FormSession->flashSuccess( 'token_success' ) ?>
	<?php $FormSession->flashErrorBag( 'login_errror' ) ?>
	<?php wp_nonce_field( 'frontend_login', 'frontend_login' ) ?>
    <input type="hidden" name="action" value="frontend_login">
    <input type="hidden" name="redirect" value="<?php echo sanitize_text_field( $_GET['redirect'] ?? '' ) ?>">
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
			<?php _e( 'E-Mail Adresse', 'ir21' ) ?>
        </label>
        <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
               id="email"
               type="email"
               name="email"
               placeholder="E-Mail Adresse"
               x-model="email"
               @keyup.debounce.500ms="ValidateEmail()"
               autocomplete="email">
        <p x-show="error.email" x-text="error.email" class="text-warning text-xs"></p>

    </div>
    <div class="mb-2">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
			<?php _e( 'Passwort', 'ir21' ) ?>
            <a class="inline align-baseline text-xs underline text-blue-500 hover:text-blue-800" href="<?php the_field( 'field_601e59c9336d7', 'option' ) ?>">
				<?php _e( '( vergessen? )', 'ir21' ) ?>
            </a>
        </label>
        <input class="shadow appearance-none border border-red-500 rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
               id="password"
               type="password"
               name="password"
               x-model="password"
               @keyup.debounce.500ms="checkCompleted()"
               placeholder="******************"
               autocomplete="current-password">
        <p x-show="error.password" x-text="error.password" class="text-warning text-xs"></p>
    </div>


    <div class="md:flex md:items-center mb-6">
        <label class="block text-gray-500 font-bold">
            <input class="mr-2 leading-tight bg-primary-100" type="checkbox" name="remember">
            <span class="text-sm"><?php _e( 'Login merken', 'ir21' ) ?></span>
        </label>
    </div>
    <div class="flex items-center justify-between">
        <button class="w-full bg-primary-100 text-center text-white font-bold py-2 px-4 focus:outline-none focus:shadow-outline"
                :class="{' cursor-not-allowed ': !completed }"
                type="submit"
                :disabled="!completed">
			<?php _e( 'einloggen', 'ir21' ) ?>
        </button>
    </div>
	<?php do_action('login_form'); ?>
</form>

<div class="py-2">
    <p class="font-medium"><?php _e( 'Noch keinen Account?', 'ir21' ) ?></p>
    <p>
        <a href="<?php echo get_field( 'field_601bc00528968', 'option' ) ?>" class="text-primary-100 underline"><?php _e( 'Hier registrieren', 'ir21' ) ?></a>
    </p>
</div>
