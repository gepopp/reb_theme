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
<div class="bg-black text-white shadow-md px-8 pt-6 pb-8 mb-4">

	<?php if ( $FormSession->has( 'register_sent_success' ) ): ?>
		<?php $FormSession->flashSuccess( 'register_sent_success' ); ?>
	<?php else: ?>

        <form method="post" action="<?php echo admin_url( 'admin-post.php' ) ?>"
              id="register-form"
              x-data="registerForm( register_data )"
              @submit.prevent="validate"
              x-ref="form">
            <h3 class="text-2xl mb-4 uppercase font-bold">
				<?php _e( 'signup', 'reb_domain' ) ?>
            </h3>
			<?php wp_nonce_field( 'frontend_register', 'frontend_register' ) ?>
            <input type="hidden" name="action" value="frontend_register">
            <input type="hidden" name="redirect" value="<?php echo $_GET['redirect'] ?? '' ?>">
            <input type="hidden" name="grecaptcha" x-model="data.token">
			<?php $FormSession->flashErrorBag( 'register_error' ); ?>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="reb-input">
                    <select name="register_gender"
                            id="register_gender"
                            required="required"
                            x-model="data.gender"
                            name="register_gender">
                        <option value=""><?php _e( 'please select', 'reb_domain' ) ?></option>
                        <option value="f"><?php _e( 'Mrs.', 'reb_domain' ) ?></option>
                        <option value="m"><?php _e( 'Mr.', 'reb_domain' ) ?></option>
                    </select>
                    <p x-show="regsiter_errors.gender" x-text="regsiter_errors.gender" class="text-warning text-xs"></p>
                </div>


                <div class="reb-input">
                    <input id="first_name"
                           type="text"
                           x-model="data.firstname"
                           name="first_name"
                           placeholder="<?php _e( 'firstname', 'reb_domain' ) ?>"/>
                    <p x-show="regsiter_errors.first_name" x-text="regsiter_errors.first_name" class="text-warning text-xs"></p>
                </div>


                <div class="reb-input">
                    <input id="last_name"
                           type="text"
                           name="last_name"
                           x-model="data.lastname"
                           placeholder="<?php _e( 'lastname', 'reb_domain' ) ?>"/>
                    <p x-show="regsiter_errors.lastname" x-text="regsiter_errors.lastname" class="text-warning text-xs"></p>
                </div>
            </div>


            <div class="reb-input">
                <input id="register_email"
                       type="email"
                       name="register_email"
                       x-model="data.email"
                       placeholder="<?php _e( 'email address', 'reb_domain' ) ?>"
                       autocomplete="email"/>
                <p x-show="regsiter_errors.email" x-text="regsiter_errors.email" class="text-warning text-xs"></p>
            </div>


            <div class="reb-input">
                <input id="register_password"
                       type="password"
                       name="password"
                       x-model="data.password"
                       placeholder="<?php _e( 'password', 'reb_domain' ) ?>"
                       autocomplete="new-password"/>
                <p class="text-xs text-gray-600" x-show="!regsiter_errors.password"><?php _e( 'At least 8 characters.', 'reb_domain' ) ?></p>
                <p x-show="regsiter_errors.password" x-text="regsiter_errors.password" class="text-warning text-xs"></p>
            </div>

            <div class="md:flex md:items-center -mt-4 mb-4 relative" x-data="{ check : false }">
                <label class="block font-bold flex items-center relative" @click="check = true">
                    <input class="hidden" type="checkbox" name="agb" :value="check">
                    <div class="w-4 h-4 rounded-full bg-white mr-2"></div>

                    <span class="text-sm">
                    <?php sprintf( _e( 'I have read the  <a href="%s" target="_blank" class="text-primary-100 underline">terms and conditions</a> of reb.institute.', 'ir21' ), get_field( 'field_601ec7cd84c47', 'option' ) ) ?>
                    </span>
                </label>
                <svg class="w-4 h-4 absolute text-logo" @click="check = false" x-show="check" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <button type="submit"
                    class="w-full bg-logo text-center text-white font-bold py-2 px-4 focus:outline-none focus:shadow-outline g-recaptcha"
            ><?php _e( 'signup', 'reb_domain' ) ?>
            </button>
        </form>
	<?php endif; ?>
</div>