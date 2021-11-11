<?php

global $FormSession;

$user = wp_get_current_user();

$image = get_field('field_5ded37c474589', 'user_' . get_current_user_id());

?>


<div class="col-span-3 lg:col-span-1">

    <form enctype="multipart/form-data" class="bg-white shadow-md px-8 pt-6 pb-8 mb-4 h-full" method="post" action="<?php echo admin_url('admin-post.php') ?>"
          x-data="profileImage('<?php echo $image['sizes']['thumbnail'] ?? false ?>')"
          @submit.prevent="submit()"
          x-ref="form">
        <h1 class="text-xl font-sans font-semibold text-gray-700 mb-4"><?php _e('Ihr Profilbild', 'ir21') ?></h1>
        <?php $FormSession->flashErrorBag('profile_image_error'); ?>
        <?php $FormSession->flashSuccess('profile_image_updated'); ?>
        <?php wp_nonce_field('profile_image', 'profile_image') ?>
        <input type="hidden" name="action" value="update_profile_image"/>
        <input class="hidden" type="file" accept="image/*" @change="fileChosen" name="profile_picture" x-ref="upload">
        <div class="flex flex-col items-center justify-center my-10">
            <div class="w-48 h-48 border border-dashed rounded-full flex items-center justify-center p-3">
                <img src="<?php echo $image['sizes']['thumbnail'] ?? '' ?>" class="rounded-full w-full h-auto" x-show="existingImage && !imageUrl">
                <template x-if="imageUrl">
                    <img :src="imageUrl"
                         class="object-cover rounded-full"
                         style="width: 100%; height: 100%;"
                    >
                </template>
            </div>
        </div>
        <div class="flex flex-col items-center justify-center">
            <p class="text-aktuelles-100 mb-5" x-text="fileError" x-show="fileError"></p>
            <button class="bg-primary-100 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" x-show="!imageUrl"
                    @click="$refs.upload.click()"
                    type="button">
                <?php _e('Neues Bild wählen', 'ir21') ?>
            </button>
            <button class="bg-primary-100 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" x-show="imageUrl"
                    type="submit">
                <span x-show="!isLoading"><?php _e('speichern', 'ir21') ?></span>
                <span x-show="isLoading" class="flex space-x-2 items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>lade</span>
                </span>
            </button>
        </div>
        <p class="text-xs mt-2"><?php _e('die mit <span class="text-warning">*</span> gekennzeichneten Felder sind Pflichtfelder.', 'ir21') ?></p>
    </form>
</div>
