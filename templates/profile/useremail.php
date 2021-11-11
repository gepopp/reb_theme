<div class="col-span-3 lg:col-span-1 lg:h-full mt-24 lg:mt-0">

    <div class="bg-white shadow-md px-8 pt-6 pb-8 mb-4 h-full"
         x-data="alterEmail()">
        <h1 class="text-xl font-sans font-semibold text-gray-700 mb-4"><?php _e('E-Mail Adresse', 'ir21') ?></h1>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2"><?php _e('Aktuelle E-Mail Adresse:', 'ir21') ?></label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-300 leading-tight focus:outline-none focus:shadow-outline cursor-not-allowed"
                   type="email"
                   value="<?php echo get_userdata(get_current_user_id())->user_email ?>"
                   disabled
            >
        </div>
        <div x-show="!pinSent">
            <label class="mt-4 block text-gray-700 text-sm font-bold mb-2" for="new_email"><?php _e('Neue E-Mail Adresse', 'ir21') ?></label>
            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   id="new_email"
                   type="email"
                   x-model="email"
            >
            <p x-show="errors.email" x-text="errors.email" class="text-warning text-xs"></p>
            <div class="mt-4 bg-primary-100 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline cursor-pointer text-center" @click="ValidateEmail()">
                <?php _e('Pin senden', 'ir21') ?>
            </div>
        </div>

        <div x-show="pinSent">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="new_email"><?php __('Pin eingeben', 'ir21') ?></label>
            <div class="grid grid-cols-5 gap-2">
                <div class="col-span-3">

                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                           id="pin"
                           type="number"
                           x-model="pin"
                    >
                    <p x-show="errors.pin" x-text="errors.pin" class="text-warning text-xs"></p>
                </div>
                <div class="col-span-2">
                    <div class="bg-primary-100 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline cursor-pointer" @click="ValidatePin()">
                        <?php _e('absenden', 'ir21') ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>