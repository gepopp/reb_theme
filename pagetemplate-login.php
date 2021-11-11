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
    <div class="container mx-auto relative px-5 md:px-0 flex justify-center pt-32">
        <div class="h-auto" x-data="loginForm(login_data)">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                <div class="bg-white p-5 shadow-xl lg:w-96">
                    <h3 class="text-xl font-medium text-gray-700 mb-4"><?php _e('Login', 'ir21') ?></h3>
                    <?php get_template_part('login', 'form') ?>
                </div>
                <div>
                    <h1 class="text-2xl font-serif mb-5"><?php the_title() ?></h1>
                    <p class="px-5 mb-10"><?php the_content(); ?></p>
                </div>
            </div>
        </div>
    </div>
<?php
get_footer();
