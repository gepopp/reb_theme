<?php global $FormSession; ?>
<script src="https://www.google.com/recaptcha/api.js?render=6Ldhsu4aAAAAAGj0UZRfizcHjtqKqPrPrxF_hsE0"></script>

<script>
    var register_data = <?php echo json_encode( $FormSession->getFormData() ) ?>;
</script>
<script>
    function onSubmit(token) {
        document.getElementById("register-form").submit();
    }
</script>
<div class="bg-white shadow-md px-8 pt-6 pb-8 mb-4">

    <?php if($FormSession->has('register_sent_success')): ?>
	    <?php $FormSession->flashSuccess( 'register_sent_success' ); ?>
    <?php else: ?>

    <form method="post" action="<?php echo admin_url( 'admin-post.php' ) ?>"
            id="register-form"
            x-data="registerForm( register_data )"
            @submit.prevent="validate"
            x-ref="form">
        <h3 class="text-xl font-medium mb-4 text-gray-700"><?php _e( 'Registrieren', 'ir21' ) ?></h3>
		<?php wp_nonce_field( 'frontend_register', 'frontend_register' ) ?>
        <input type="hidden" name="action" value="frontend_register">
        <input type="hidden" name="redirect" value="<?php echo $_GET['redirect'] ?? '' ?>">
        <input type="hidden" name="grecaptcha" x-model="data.token">
		<?php $FormSession->flashErrorBag( 'register_error' ); ?>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">

            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="register_gender">
					<?php _e( 'Anrede', 'ir21' ) ?> <span class="text-warning">*</span>
                </label>
                <select name="register_gender"
                        id="register_gender"
                        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                        required="required"
                        x-model="data.gender"
                        name="register_gender">
                    <option value=""><?php _e( 'Bitte w&auml;hlen', 'ir21' ) ?></option>
                    <option value="f"><?php _e( 'Frau', 'ir21' ) ?></option>
                    <option value="m"><?php _e( 'Herr', 'ir21' ) ?></option>
                </select>
                <p x-show="regsiter_errors.gender" x-text="regsiter_errors.gender" class="text-warning text-xs"></p>
            </div>


            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="first_name"><?php _e( 'Vorname', 'ir21' ) ?></label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="first_name"
                       type="text"
                       x-model="data.firstname"
                       name="first_name"
                       placeholder="Vorname"/>
                <p x-show="regsiter_errors.first_name" x-text="regsiter_errors.first_name" class="text-warning text-xs"></p>
            </div>


            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="last_name"><?php _e( 'Nachname', 'ir21' ) ?>
                    <span class="text-warning">*</span></label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       id="last_name"
                       type="text"
                       name="last_name"
                       x-model="data.lastname"
                       placeholder="Nachname"/>
                <p x-show="regsiter_errors.lastname" x-text="regsiter_errors.lastname" class="text-warning text-xs"></p>
            </div>


        </div>


        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="register_email"><?php _e( 'E-Mail Adresse', 'ir21' ) ?>
                <span class="text-warning">*</span></label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   id="register_email"
                   type="email"
                   name="register_email"
                   x-model="data.email"
                   placeholder="E-Mail Adresse"
                   autocomplete="email"/>
            <p x-show="regsiter_errors.email" x-text="regsiter_errors.email" class="text-warning text-xs"></p>
        </div>


        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="register_password">
				<?php _e( 'Passwort', 'ir21' ) ?> <span class="text-warning">*</span>
            </label>
            <input class="shadow appearance-none border border-red-500 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                   id="register_password"
                   type="password"
                   name="password"
                   x-model="data.password"
                   placeholder="******************"
                   autocomplete="new-password"/>
            <p class="text-xs text-gray-600" x-show="!regsiter_errors.password"><?php _e( 'Mindestens 8 Zeichen.', 'ir21' ) ?></p>
            <p x-show="regsiter_errors.password" x-text="regsiter_errors.password" class="text-warning text-xs"></p>
        </div>


        <div class="md:flex md:items-center mb-4">
            <label class="block text-gray-500 font-bold">
                <input class="mr-2 leading-tight bg-primary-100" type="checkbox" name="agb" required>
                <span class="text-sm">
                <?php sprintf( _e( 'Ich bin der <a href="%s" target="_blank" class="text-primary-100 underline">Datenschutzerklärung</a> der unabhängigen Immobilien Redaktion einverstanden.', 'ir21' ), get_field( 'field_601ec7cd84c47', 'option' ) ) ?>
                <span class="text-warning">*</span>
            </span>
            </label>
        </div>
        <button type="submit"
                class="block bg-primary-100 text-white text-center py-3 font-semibold w-full g-recaptcha"
                >registrieren</button>
    </form>
    <?php endif; ?>
</div>