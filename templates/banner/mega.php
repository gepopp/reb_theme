<?php

$today = date('Ymd');
$banner_args = [
    'post_type'      => 'ir_ad',
    'posts_per_page' => 1,
    'tax_query'      => [
        'relation' => 'and',
        [
            'taxonomy'         => 'position',
            'terms'            => 'mega-banner',
            'field'            => 'slug',
            'include_children' => false,
            'operator'         => 'IN',
        ],

    ],
    'meta_query'     => [
        'relation' => 'AND',
        [
            'key'     => 'start',
            'compare' => '<=',
            'value'   => $today,
        ],
        [
            'key'     => 'ende',
            'compare' => '>=',
            'value'   => $today,
        ],
        [
            'key'   => 'banner_status', // name of custom field
            'value' => [3, 5],
        ],
    ],
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
];
$query_banner = new WP_Query($banner_args);

$cats = wp_get_post_categories(get_the_ID());

$live = !empty(array_filter($cats, function ($cat){
    $filter = get_field('field_60733fe611fac', 'option');
    $filter = is_array($filter) ? $filter : [];

    if(in_array($cat, $filter )){
        return $cat;
    }
}));


?>
<?php if (!is_single() || (is_single() && get_post_format() == 'video') || $live): ?>
    <div class="container mx-auto mt-12 mb-12 px-5 lg:px-0">
        <p class="text-xs text-gray-300"><?php _e('Werbung', 'ir21') ?></p>
        <div class="flex flex-col lg:flex-row p-5 border-2 border-primary-100">
            <?php if ($query_banner->have_posts()): ?>
                <?php while ($query_banner->have_posts()): ?>
                    <?php $query_banner->the_post(); ?>
                    <div class="w-full">


                        <?php if (get_field('field_6002b1de949da')): ?>
                            <embed src="<?php echo get_field('field_6002b1de949da') ?>" width="100%" height="auto" class="overflow-hidden hidden xl:block" style="max-height: 110px;">
                        <?php else: ?>
                            <a href="<?php the_field('field_5c6325e38e0aa') ?>" class="hidden xl:block">
                                <img src="<?php echo get_field('field_6002b1af949d9') ?>" class="w-full h-auto">
                            </a>
                        <?php endif; ?>

                        <?php if (get_field('field_6002b1f6949db')): ?>
                            <embed src="<?php echo get_field('field_6002b1f6949db') ?>" width="100%" height="auto" class="overflow-hidden hidden lg:block xl:hidden" style="max-height: 110px">
                        <?php else: ?>
                            <a href="<?php the_field('field_5c6325e38e0aa') ?>" class="hidden lg:block xl:hidden">
                                <img src="<?php echo get_field('field_60011a6a053b7') ?>" class="w-full h-auto">
                            </a>
                        <?php endif; ?>

                        <?php if (get_field('field_6002b205949dc')): ?>
                            <embed src="<?php echo get_field('field_6002b205949dc') ?>" width="100%" height="auto" class="overflow-hidden hidden sm:block lg:hidden" style="max-height: 110px">
                        <?php else: ?>
                            <a href="<?php the_field('field_5c6325e38e0aa') ?>" class="hidden sm:block lg:hidden">
                                <img src="<?php echo get_field('field_60011a7d053b8') ?>" class="w-full h-auto">
                            </a>
                        <?php endif; ?>

                        <?php if (get_field('field_6002b224949dd')): ?>
                            <embed src="<?php echo get_field('field_6002b224949dd') ?>" width="100%" height="auto" class="overflow-hidden block sm:hidden" style="max-height: 250px">
                        <?php else: ?>
                            <a href="<?php the_field('field_5c6325e38e0aa') ?>" class="block sm:hidden">
                                <img src="<?php echo the_field('field_5f0d5b0270f63') ?>" class="w-full h-auto">
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <div class="container mx-auto">
        <p class="text-xs text-gray-300"><?php _e('Werbung', 'ir21') ?></p>
        <div class="flex flex-col lg:flex-row p-5 border-2 border-primary-100">
            <?php if ($query_banner->have_posts()): ?>
                <?php while ($query_banner->have_posts()): ?>
                    <?php $query_banner->the_post(); ?>
                    <div class="w-full">

                       <?php if (get_field('field_6002b205949dc')): ?>
                            <embed src="<?php echo get_field('field_6002b205949dc') ?>" width="100%" height="auto" class="overflow-hidden hidden sm:block" style="max-height: 110px">
                        <?php else: ?>
                            <a href="<?php the_field('field_5c6325e38e0aa') ?>" class="hidden sm:block">
                                <img src="<?php echo get_field('field_60011a7d053b8') ?>" class="w-full h-auto">
                            </a>
                        <?php endif; ?>

                        <?php if (get_field('field_6002b224949dd')): ?>
                            <embed src="<?php echo get_field('field_6002b224949dd') ?>" width="100%" height="auto" class="overflow-hidden block sm:hidden" style="max-height: 250px">
                        <?php else: ?>
                            <a href="<?php the_field('field_5c6325e38e0aa') ?>" class="block sm:hidden">
                                <img src="<?php echo the_field('field_5f0d5b0270f63') ?>" class="w-full h-auto">
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>
<?php wp_reset_postdata(); ?>