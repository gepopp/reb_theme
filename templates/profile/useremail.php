<div class="col-span-3 lg:col-span-1 lg:h-full mt-24 lg:mt-0">
    <div class="bg-black text-white shadow-md px-8 pt-6 pb-8 mb-4 h-full"
         x-data="alterEmail()">
        <h1 class="text-3xl font-bold mb-5 uppercase"><?php _e('email addresss', 'reb_domain') ?></h1>

        <div class="reb-input">
            <input type="email"
                   value="<?php echo get_userdata(get_current_user_id())->user_email ?>"
                   disabled
            >
        </div>

        <div x-show="!pinSent" class="reb-input">
            <input placeholder="<?php _e('New address', 'reb_domain') ?>"
                   id="new_email"
                   type="email"
                   x-model="email"
            >
            <p x-show="errors.email" x-text="errors.email" class="text-warning text-xs"></p>

        </div>
        <div class="cursor-pointer w-full bg-logo text-center text-white font-bold py-2 px-4 focus:outline-none focus:shadow-outline g-recaptcha flex justify-center items-center"
             @click="ValidateEmail()"
             x-show="!pinSent">
		    <?php _e('Send pin', 'reb_domain') ?>
        </div>

        <div x-show="pinSent" class="reb-input">
            <div class="grid grid-cols-5 gap-2">
                <div class="col-span-3">
                    <input placeholder="<?php _e('enter pin', 'reb_domain') ?>"
                           id="pin"
                           type="number"
                           x-model="pin"
                    >
                    <p x-show="errors.pin" x-text="errors.pin" class="text-red-900 text-xs"></p>
                </div>
                <div class="col-span-2">
                    <div class="cursor-pointer w-full bg-logo text-center text-white font-bold py-2 px-4 focus:outline-none focus:shadow-outline g-recaptcha flex justify-center items-center"
                    @click="ValidatePin()">
                        <?php _e('submit', 'reb_domain') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>