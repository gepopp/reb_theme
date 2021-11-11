<?php if ( ! is_user_logged_in() ): ?>
    <div class="text-center space-y-3">
        <h3 class="font-semibold text-primary-100 text-xl">Unternehmensdaten können nur von eingeloggten Usern aktualisert werden.</h3>
        <p class="text-primary-100">Jetzt</p>
        <a href="<?php echo add_query_arg( 'redirect', get_the_permalink(), get_field( 'field_601bbffe28967', 'option' ) ) ?>"
           class="bg-white border border-primary-100-100 py-3 px-5 text-center block">
            einloggen
        </a>
        <p class="text-primary-100">oder
            <a href="<?php the_field( 'field_601bc00528968', 'option' ); ?>" class="underline">hier registrieren</a></p>

    </div>
<?php else: ?>

	<?php
    global $FormSession;
    $uploaderror = implode('<br>', $FormSession->content['errorBag']['uploaderror'] ?? []);
    $formdata = $FormSession->getFormData();
    $FormSession->flashErrorBag( 'global_error' ) ?>
    <form method="post" action="<?php echo admin_url( 'admin-post.php' ) ?>" enctype="multipart/form-data" class="w-full" id="zur-person-update">
		<?php wp_nonce_field( 'zur_person_update', 'zur_person_update_nonce' ) ?>
        <input type="hidden" name="action" value="save_zur_person_update">
        <input type="hidden" name="zur_person_id" value="<?php echo get_the_ID() ?>">

        <div class="mb-5 pb-3 border-b border-primary-100">
            <h3 class="mb-3">Bild</h3>
            <input type="file" accept="image/*" name="image"/>
            <p class="text-red-900 text-xs"><?php echo $uploaderror ?></p>
        </div>
        <div class="mb-5 pb-3 border-b border-primary-100 grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <h3 class="mb-3">Vorname</h3>
                <input type="text" name="firstname" class="w-full p-3" value="<?php echo $formdata['firstname'] ?? '' ?>"/>
            </div>
            <div>
                <h3 class="mb-3">Nachname</h3>
                <input type="text" name="lastname" class="w-full p-3" value="<?php echo $formdata['lastname'] ?? '' ?>"/>
            </div>
        </div>
        <div class="mb-5 pb-3 border-b border-primary-100 grid grid-cols-1 md:grid-cols-2 gap-5">
            <div>
                <h3 class="mb-3">Unterehmen</h3>
                <input type="text" name="company" class="w-full p-3" value="<?php echo $formdata['company'] ?? '' ?>"/>
            </div>
            <div>
                <h3 class="mb-3">Position</h3>
                <input type="text" name="position" class="w-full p-3" value="<?php echo $formdata['position'] ?? '' ?>"/>
            </div>
        </div>

        <div class="mb-5 pb-3 border-b border-primary-100">
            <h3 class="mb-3">Personenbeschreibung / Karriereschritte</h3>
            <textarea name="description" class="w-full p-3" rows="10">
                <?php echo $formdata['description'] ?? '' ?>
            </textarea>
            <label class="block w-full">Lebenslauf hochladen</label>
                <input type="file" accept="application/pdf" name="lebenslauf"/>
                <p class="text-red-900 text-xs">Hier sind nur PDF mit maximal 2 MB erlaubt.</p>
        </div>

        <div class="mb-5 pb-3 border-b border-primary-100">
            <h3 class="mb-3">Link zur Ihrer Webseite</h3>
            <input type="url" name="companylink" class="w-full p-3" value="<?php echo $formdata['companylink'] ?? '' ?>">
        </div>
        <div class="mb-5 pb-3 border-b border-primary-100">
            <label class="flex space-x-2 w-full items-center">
                <input type="checkbox" name="accept" class="p-3" required>
                <span>Ich akzeptiere die <a href="/agb" target="_blank">AGB</a> und die <a href="/datenschutz" target="_blank">Datenschutzerklärung</a>
                        der unabhägigen Immobilien Redaktion.<span class="text-red-700">*</span></span>
        </div>
        <button type="submit" class="button button-primary block">Vorschlag einreichen</button>
    </form>
<?php endif; ?>
