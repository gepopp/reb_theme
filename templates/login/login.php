<?php

global $FormSession;

?>
<script>
    var login_data = <?php echo json_encode($FormSession->getFormData()) ?>;
    var errorbag = <?php echo json_encode($FormSession->get('errorBag')) ?>;
    var successMessage = <?php echo  "'" . $FormSession->get('token_success') . "'" ?>;
</script>


<div class="col-span-6 lg:col-span-3" x-data="loginForm(login_data, errorbag, successMessage)">
    <h1 class="text-2xl font-serif font-semibold mb-5"><?php _e('Einloggen', 'ir21') ?></h1>
    <div class="bg-white shadow-md px-8 pt-6 pb-8 mb-4">
        <?php get_template_part('login', 'form') ?>
    </div>
</div>