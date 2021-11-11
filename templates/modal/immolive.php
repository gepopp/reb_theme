<?php

use Overtrue\Socialite\SocialiteManager;

?>

<div x-data="{ show: false, user : false, id : false }"
     x-init="
            window.addEventListener('register-immolive', (e) => {
                show = true;
                user = e.detail.user;
                id = e.detail.id;
            })
        ">

    <!-- This example requires Tailwind CSS v2.0+ -->
    <div class="fixed z-10 inset-0 overflow-y-auto"
         x-show="show"
         x-cloak>
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!--
              Background overlay, show/hide based on modal state.

              Entering: "ease-out duration-300"
                From: "opacity-0"
                To: "opacity-100"
              Leaving: "ease-in duration-200"
                From: "opacity-100"
                To: "opacity-0"
            -->
            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <!--
              Modal panel, show/hide based on modal state.

              Entering: "ease-out duration-300"
                From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                To: "opacity-100 translate-y-0 sm:scale-100"
              Leaving: "ease-in duration-200"
                From: "opacity-100 translate-y-0 sm:scale-100"
                To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            -->
            <div class="inline-block align-bottom bg-white border-15 border-primary-100 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                 role="dialog" aria-modal="true"
                 aria-labelledby="modal-headline"
                 @click.away="show = false">


                <div x-show="!user" class="p-5">
                    <div class="flex space-x-4 items-center">
                        <svg class="w-12 h-12 text-primary-100" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                        <h2 class="font-semibold text-xl mb-4 font-sans text-primary-100"><?php _e( 'Um sich zu unseren ImmoLive Webinaren anmelden zu können müssen Sie sich einloggen.', 'ir21' ) ?></h2>
                    </div>
                    <p class="mb-4"><?php _e( 'Sie haben noch keinen Account bei der Immobilien Redaktion? Kein Problen, einfach, schnell und', 'ir21' ) ?>
                        <a class="text-primary-100 underline"
                           href="<?php echo add_query_arg( [ 'redirect' => urlencode( get_permalink() ) ], get_field( 'field_6013cf36d4689', 'option' ) ) ?>">
							<?php _e( 'kostenlos registrieren', 'ir21' ) ?>
                        </a>

                        .</p>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="col-span-2">

							<?php
							$ref      = $_GET['ref'] ?? 'none';
							$redirect = urlencode( add_query_arg( [ 'ref' => $ref ], get_field( 'field_601e5f56775db', 'option' ) ) )
							?>
                            <a href="<?php echo add_query_arg( [ 'redirect' => $redirect ], get_field( 'field_601bbffe28967', 'option' ) ) ?>"
                               class="block bg-primary-100 text-white font-semibold text-center shadow-xl py-3 my-5 text-lg focus:outline-none focus:shadow-outline w-full text-center cursor-pointer">
								<?php _e( 'E-Mail login', 'ir21' ) ?>
                            </a>
                        </div>

                        <div class="bg-primary-100 bg-opacity-5 p-5">
                            <div x-show="user">
								<?php $user = wp_get_current_user(); ?>
                                <h2 class="font-sans text-primary-100 font-semibold text-xl mb-4"><?php echo $user->first_name ?><?php echo $user->last_name ?><?php _e( ', wir freuen uns auf Ihre Teilnahme!', 'ir21' ) ?></h2>
                                <form action="<?php echo admin_url( 'admin-post.php' ) ?>" method="post">
									<?php wp_nonce_field( 'subscribe_immolive', 'subscribe_immolive' ) ?>
                                    <input type="hidden" name="action" value="subscribe_immolive">
                                    <input type="hidden" name="immolive_id" x-model="id">
                                    <input type="hidden" name="referer" value="<?php echo isset( $_GET['ref'] ) ? substr( sanitize_text_field( $_GET['ref'] ), 0, 8 ) : '' ?>">

                                    <label class="block text-gray-700 text-sm font-bold mb-2" for="question"><?php _e( 'Ihre Frage an unser Poduim', 'ir21' ) ?></label>
                                    <textarea rows="5" id="question" name="question" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mb-4"></textarea>

                                    <label class="mb-4 block flex space-x-2" for="confirm">
                                        <input class="mt-1" type="checkbox" name="confirm" id="confirm" required>
                                        <span class="inline text-gray-700 text-sm font-bold mb-2">
                                           <?php _e( ' Ja, ich nehme an diesem Live Webinar über Zoom teil und bin mit den', 'ir21' ) ?>
                                                <a href="<?php echo get_field( 'field_601ec7cd84c47', 'option' ) ?>" target="_blank" class="text-primary-100 underline">
                                                <?php _e( 'Datenschutzbestimmungen', 'ir21' ) ?>
                                                </a>
                                                <?php _e( 'der Immobilienredaktion sowie meiner Registrierung auf Zoom (', 'ir21' ) ?>
                                                <a href="https://us02web.zoom.us/privacy" target="_blank" class="text-primary-100 underline">
                                                    <?php _e( 'Datenschutzrichtlinien', 'ir21' ) ?>
                                                </a>
                                                <?php _e( 'und', 'ir21' ) ?>
                                                <a href="https://us02web.zoom.us/terms" target="_blank" class="text-primary-100 underline">
                                                <?php _e( 'Nutzungsbedingungen', 'ir21' ) ?>
                                                </a>
                                                <?php _e( ') einverstanden.', 'ir21' ) ?>
                                            </span>
                                    </label>
                                    <button type="submit" class="block w-full bg-primary-100 text-white font-semibold py-3 px-3 focus:outline-none"><?php _e( 'jetzt anmelden', 'ir21' ) ?></button>
                                </form>
                            </div>


                            <div class="bg-gray-50 pt-5 flex">
                                <button type="button"
                                        class="w-full inline-flex justify-center border border-primary-100 shadow-sm px-4 py-2 text-base font-medium text-primary-100 hover:bg-red-700 focus:outline-none focus:ring-2"
                                        @click="show = false">
									<?php _e( 'abbrechen', 'ir21' ) ?>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

