<?php
/**
 * Template Name: Loginpage
 */

if (isset($_GET['token'])) {
    $user = new \reb_livestream_classes\Boot\User();
    $user->activate_user();
}

get_header();

global $FormSession;
?>
<script>
    var login_data = <?php echo json_encode($FormSession->getFormData()) ?>;
</script>
    <div class="container mx-auto relative px-5 md:px-0 flex justify-center">
        <div class="h-auto" x-data="loginForm(login_data)">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <div class="bg-black text-white p-5 shadow-xl lg:w-96">
                    <h1 class="text-2xl mb-4 uppercase font-bold">
                        <?php _e('login', 'reb_domain') ?>
                    </h1>
                    <?php get_template_part('login', 'form') ?>
                </div>
                <div class="order-first lg:order-last">
                    <h1 class="text-3xl font-bold mb-5">
                        <?php the_title() ?>
                    </h1>
                    <div class="content">
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php
get_footer();
