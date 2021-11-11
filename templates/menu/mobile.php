<div class="absolute left-0 top-0 mt-10 w-full bg-white pt-16 z-30 shadow-2xl" x-show.transition.ease-in-out.300ms="showMobile" @click.away="showMobile = false" @mouseleave="showMobile = false" x-cloak>
    <div class="p-5 relative">
        <div class="absolute top-0 right-0 flex justify-end -mt-10 mr-5">
            <div class="w-10 h-10 rounded-full bg-primary-100 flex justify-center items-center" @click="showMobile = false">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
        </div>
        <div class="py-5 mb-5 border-b border-primary-100 font-semibold uppercase">
            <?php
            wp_nav_menu([
                'theme_location'  => 'primary',
                'container'       => 'nav',
                'container_class' => 'mobile-menu',
                'depth'           => 1,
                'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
            ]);
            ?>
        </div>

        <div class="flex flex-col space-y-4 my-5">
            <?php if (is_user_logged_in()): ?>
            <div>
                <a class="block text-center text-white bg-primary-100 py-3 font-medium" href="<?php the_field('field_601bc4580a4fc', 'option'); ?>"><?php _e('Profil', 'ir21') ?></a>
            </div>
                <form method="post" action="<?php echo admin_url('admin-post.php') ?>">
                    <input type="hidden" name="action" value="frontent_logout">
                    <button class="block w-full text-center text-white bg-primary-100 py-3 font-medium" type="submit"><?php _e('logout', 'ir21') ?></button>
                </form>
            <?php else: ?>
                <div>
                    <a class="block text-center text-white bg-primary-100 py-3 font-medium" href="<?php the_field('field_601bbffe28967', 'option'); ?>"><?php _e('Login', 'ir21') ?></a>
                </div>
                <div>
                    <a class="block text-center text-white bg-primary-100 py-3 font-medium" href="<?php the_field('field_601bc00528968', 'option') ?>"><?php _e('Registrieren', 'ir21') ?></a>
                </div>
            <?php endif; ?>
        </div>

        <ul class="flex justify-between px-3">
            <li class="uppercase text-white mr-3 w-10 h-10">
                <a href="https://www.facebook.com/ImmoRedaktion" target="_blank">
                    <?php get_template_part('icons', 'facebook') ?>
                </a>
            </li>
            <li class="uppercase text-white mr-3 w-10 h-10">
                <a href="https://twitter.com/ImmoRedaktion" target="_blank">
                    <?php get_template_part('icons', 'twitter') ?>
                </a>
            </li>
            <li class="uppercase text-white mr-3 w-10 h-10">
                <a href="https://www.linkedin.com/company/die-unabhaengige-immobilien-redaktion/" target="_blank">
                    <?php get_template_part('icons', 'linkedin') ?>
                </a>
            </li>
        </ul>
    </div>
</div>