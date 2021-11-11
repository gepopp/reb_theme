<div class="pt-5">
    <h3 class="font-semibold text-primary-100 font-serif text-xl text-center">Zu diesem Livestream anmelden</h3>
    <p class="text-sm my-3">
        Um bei unseren Podiumsdiskussionen und Interviews live dabei sein zu können, ist eine Anmeldung erforderlich. Erstellen Sie jetzt Ihren Account auf der Immobilien Redaktion und melden Sie sich an.
    </p>

	<?php if ( ! is_user_logged_in() ): ?>

        <div class="p-5">
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <a href="<?php echo add_query_arg( 'redirect', get_the_permalink(), get_field( 'field_6013cf1ad4688', 'option' ) ) ?>"
                       class="block w-full bg-primary-100 text-center text-white font-semibold p-3">
                        login
                    </a>
                </div>
                <div>
                    <a href="<?php echo add_query_arg( 'redirect', get_the_permalink(), get_field( 'field_601bc00528968', 'option' ) ) ?>"
                       class="block w-full bg-white text-center text-primary-100 border border-primary-100 font-semibold p-3">
                        registrieren
                    </a>
                </div>
            </div>
        </div>

	<?php else: ?>
		<?php
		global $FormSession;
		$FormSession->flashErrorBag( 'newsletter' );
		$FormSession->flashSuccess( 'newsletter' );

		$user = wp_get_current_user();
		?>
        <div x-data="immolivesubscription(<?php echo get_the_ID() ?>, '<?php echo $user->display_name ?>', '<?php echo $user->user_email ?>')" x-cloak x-init="init()">
            <form action="<?php echo admin_url( 'admin-post.php' ) ?>"
                  method="post"
                  x-show="!load && !error && !success"
                  @submit.prevent="validate()"
            >
				<?php wp_nonce_field( 'newsletter', 'newsletter' ) ?>
                <input type="hidden" name="action" value="get_immolive_ics">
                <input type="hidden" name="post_id" value="<?php echo get_the_ID() ?>">
                <div class="flex flex-col">
                    <div class="mt-4">
                        <label class="text-sm">Name<span class="text-red-500">*</span></label>
                        <input
                                type="text"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                name="name"
                                required
                                x-model="name"
                                @keyup="validate()"
                        >
                        <p class="text-xs text-red-500" x-html="errors.name ? errors.name : '&nbsp;'"></p>
                    </div>
                    <div class="mt-4">
                        <label class="text-sm">E-Mail Adresse<span class="text-red-500">*</span></label>
                        <input
                                type="email"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                name="email"
                                required
                                x-model="email"
                                @keyup="validate()"
                        >
                        <p class="text-xs text-red-500" x-html="errors.email ? errors.email : '&nbsp;'"></p>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="text-sm">Anmerkung</label>
                    <textarea
                            class="border border-gray-900 w-full p-3 shadow"
                            name="question"
                            placeholder="Ihre Frage an unsere Studiogäste."
                            x-model="question"
                    ></textarea>
                    <p class="text-xs text-red-900" x-html="errors.question ? errors.question : '&nbsp;'"></p>
                </div>
                <button type="submit" class="bg-primary-100 text-white flex-none p-3 w-full text-center">jetzt anmelden</button>
                <span class="text-red-500 text-xs">Mit * gekenntzeichnte Felder sind Pflichtfelder</span>
            </form>


            <div class="w-full h-64 flex justify-center items-center" x-show="load">
                <div>
                    <svg class="w-12 h-12 text-primary-100 animate-spin mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-primary-100 font-semibold">Lade..</p>
                </div>
            </div>

            <div class="w-full h-64 flex justify-center items-center" x-show="error != ''">
                <div class="text-center">
                    <svg class="w-12 h-12 text-red-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-500 font-semibold" x-text="error"></p>
                    <p class="text-red-500 font-semibold">Bitte versuchen Sie es später erneut.</p>
                </div>
            </div>

            <div class="w-full h-64 flex justify-center items-center" x-show="success">
                <div class="text-center">
                    <svg class="w-12 h-12 text-green-500 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-green-500 font-semibold" x-text="success"></p>
                </div>
            </div>


        </div>
	<?php endif; ?>
</div>
