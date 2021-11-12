<?php
/**
 * The header for our theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Freeshifter
 */

namespace reb_livestream_theme;
global $FormSession;

?><!DOCTYPE html>
<html <?php

language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <meta name="keywords" content="immobilienredaktion, immobilienmagazin, Immobilien Redaktion, Immobilien Magazin, Wien, Immobilien, Immoflash, ImmoWelt, International, Investment, Markt, Mieten, Wohnen, Österreich, Deutschland, Real Estate">
    <meta name="description" content="<?php echo get_the_excerpt() ?>">
    <link rel="icon" type="image/png" href="<?= get_stylesheet_directory_uri() . '/assets/images/favicon.png'; ?>">
    <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php wp_head(); ?>
    <script>
        window.ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ) ?>';
    </script>
    <meta property="og:url" content="<?php the_permalink(); ?>"/>
    <meta property="og:type" content="article"/>
    <meta property="og:title" content="<?php the_title() ?>"/>
    <meta property="og:description" content="<?php echo get_the_excerpt(); ?>"/>
    <meta property="og:image" content="<?php the_post_thumbnail_url( 'article' ); ?>"/>
    <meta property="og:image:width" content="600"/>
    <meta property="og:image:height" content="450"/>
    <script src="https://player.vimeo.com/api/player.js"></script>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async="" src="https://www.googletagmanager.com/gtag/js?id=UA-135909385-1"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <script>

        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag("js", new Date());

        gtag("config", "UA-135909385-1", {"anonymize_ip": true});

    </script>
</head>

<body <?php body_class(); ?> itemscope itemtype="https://schema.org/WebPage">

<header class="header w-full">
    <div class="flex justify-between w-full">
        <div class="logo-holder p-8">
            <a href="<?php echo home_url() ?>">
                <img src="<?php echo get_stylesheet_directory_uri() ?>/assets/images/real-estate-brand-institute_logo_talks.svg" class="w-36 md:w-48 lg:w-64"/>
            </a>
        </div>
        <div class="p-8 text-logo">
            <ul class="space-x-5 hidden md:flex">
				<?php
				if ( ! is_user_logged_in() ):
					?>
                    <li class="text-lg font-semibold">
                        <a href="<?php the_field( 'field_601bbffe28967', 'option' ); ?>"><?php _e( 'signin', 'reb_domain' ) ?></a>
                    </li>
                    <li class="text-lg font-semibold">
                        <a href="<?php the_field( 'field_601bc00528968', 'option' ) ?>" class="bg-logo text-white px-5 py-3 tex-center"><?php _e( 'signup', 'reb_domain' ) ?></a>
                    </li>
				<?php else: ?>
                    <li class="text-lg font-semibold">
                        <a href="<?php the_field( 'field_601bc4580a4fc', 'option' ); ?>"><?php _e( 'profile', 'reb_domain' ) ?></a>
                    </li>
                    <li class="text-lg font-semibold">
                        <form method="post" action="<?php echo admin_url( 'admin-post.php' ) ?>">
                            <input type="hidden" name="action" value="frontent_logout">
                            <button class="font-semibold" type="submit"><?php _e( 'logout', 'reb_domain' ) ?></button>
                        </form>
                    </li>
				<?php endif; ?>
                <li class="text-lg font-semibold flex items-center">
                    <a href="https://reb.institute" class="flex items-center">
                        <span><?php _e( 'back to reb.institiute' ) ?></span>
                        <svg class="w-6 h-6 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                        </svg>
                    </a>
                </li>
            </ul>
            <div x-data="{ show : false }" x-cloak>
                <div class="p-8 md:hidden">
                    <div @click="show = !show" x-show="!show">
                        <svg class="w-12 h-12 text-logo" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </div>
                    <div @click="show = !show" x-show="show">
                        <svg class="w-12 h-12 text-logo" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </div>
                </div>
                <div class="absolute top-0 right-0 h-screen border-l-2 border-logo bg-white z-50 p-8 shadow-2xl animate__animated animate__slideInRight md:hidden" x-show="show">
                    <ul class="flex flex-col h-full">
                        <li @click="show = !show" class="w-full flex justify-end">
                            <svg class="w-12 h-12 text-logo" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </li>
						<?php
						if ( ! is_user_logged_in() ):
							?>
                            <li class="text-lg font-semibold w-full mb-8">
                                <a href="<?php the_field( 'field_601bbffe28967', 'option' ); ?>"><?php _e( 'signin', 'reb_domain' ) ?></a>
                            </li>
                            <li class="text-lg font-semibold w-full mb-8">
                                <a href="<?php the_field( 'field_601bc00528968', 'option' ) ?>" class="bg-logo text-white px-5 py-3 tex-center"><?php _e( 'signup', 'reb_domain' ) ?></a>
                            </li>
						<?php else: ?>
                            <li class="text-lg font-semibold w-full mb-8">
                                <a href="<?php the_field( 'field_601bc4580a4fc', 'option' ); ?>"><?php _e( 'profile', 'reb_domain' ) ?></a>
                            </li>
                            <li class="text-lg font-semibold w-full mb-8">
                                <form method="post" action="<?php echo admin_url( 'admin-post.php' ) ?>">
                                    <input type="hidden" name="action" value="frontent_logout">
                                    <button class="font-semibold" type="submit"><?php _e( 'logout', 'reb_domain' ) ?></button>
                                </form>
                            </li>
						<?php endif; ?>
                        <li class="text-lg font-semibold flex items-center mt-auto">
                            <a href="https://reb.institute" class="flex items-center">
                                <span><?php _e( 'back to reb.institiute' ) ?></span>
                                <svg class="w-6 h-6 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
<main>
