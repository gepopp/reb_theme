<?php get_template_part('article', 'author') ?>

<script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "NewsArticle",
        "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "<?php echo the_permalink() ?>"
        },
        "headline": "<?php echo the_title() ?>",
        "image": [
            "<?php echo the_post_thumbnail_url('thumbnail') ?>",
            "<?php echo the_post_thumbnail_url('large') ?>",
            "<?php echo the_post_thumbnail_url('full') ?>"
        ],
        "datePublished": "<?php echo the_time('c') ?>",
        "dateModified": "<?php echo the_modified_time('c') ?>",
        "author": {
            "@type": "Person",
            "name": "<?php the_author_meta('full_name'); ?>"
        },
        "publisher": {
            "@type": "Organization",
            "name": "Die unabhängige Immobilien Redaktion",
            "logo": {
                "@type": "ImageObject",
                "url": "<?php echo get_stylesheet_directory_uri() . '/assests/images/logo.png'?>"
            }
        }
    }
</script>


<div class="container mx-auto mt-20">
    <div class="grid grid-cols-5 gap-4">
        <div class="hidden lg:block"></div>
    </div>
</div>

<div class="container mx-auto">
    <div class="grid grid-cols-5 gap-4">
        <div class="hidden lg:block"></div>
        <div class="col-span-5 lg:col-span-3">
            <?php get_template_part('banner', 'mega') ?>
        </div>
    </div>
</div>


<div class="container mx-auto">
    <div class="grid grid-cols-5 gap-4">
        <div class="hidden lg:block"></div>
        <div class="col-span-5 lg:col-span-3  py-5">
            <div class="relative">
                <?php the_post_thumbnail('custom-thumbnail', ['class' => 'mt-5 w-full h-auto']); ?>
                <?php if (get_field('field_5c6cfbd7106c1', get_post_thumbnail_id(get_the_ID()))): ?>
                    <p class="absolute bottom-0 right-0 transform rotate-90 text-white mr-2" style=" transform-origin: right;">&copy <?php echo get_field('field_5c6cfbd7106c1', get_post_thumbnail_id(get_the_ID())) ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="hidden lg:block"></div>
    </div>
</div>