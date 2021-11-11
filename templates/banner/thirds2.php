<?php
$today = date('Ymd');
$banner_small = get_posts([
    'post_type'      => 'ir_ad',
    'posts_per_page' => 1,
    'offset'         => 0,
    'tax_query'      => [
        'relation' => 'and',
        [
            'taxonomy'         => 'position',
            'terms'            => 'startseite-horizontal-klein',
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
]);


$banner_large = get_posts([
    'post_type'      => 'ir_ad',
    'posts_per_page' => 1,
    'offset'         => 0,
    'tax_query'      => [
        'relation' => 'and',
        [
            'taxonomy'         => 'position',
            'terms'            => 'startseite-horizontal',
            'field'            => 'slug',
            'meta_query'       => [
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
            'include_children' => false,
            'operator'         => 'IN',
        ],
        'orderby'  => 'menu_order',
        'order'    => 'ASC',
    ],
]);

?>
<div class="px-5 lg:px-0">
    <div class="container mx-auto mt-20">
        <p class="text-xs text-gray-300"><?php _e('Werbung', 'ir21') ?></p>
        <div class="hidden lg:flex flex-col lg:flex-row p-5 border-2 border-primary-100">
            <?php if (!empty($banner_large)): ?>
                <div class="w-full lg:w-2/3 lg:pr-5 pb-5 lg:pb-0 hidden lg:block">
                    <a href="<?php the_field('field_5c6325e38e0aa', $banner_large[0]->ID) ?>">
                        <img src="<?php echo get_the_post_thumbnail_url($banner_large[0]->ID, 'full'); ?>" class="w-full h-auto">
                    </a>
                </div>
            <?php endif; ?>


            <?php if (!empty($banner_small)): ?>
                <div class="w-full lg:w-1/3 lg:pl-5 hidden lg:block">
                    <a href="<?php the_field('field_5c6325e38e0aa', $banner_small[0]->ID) ?>">
                        <img src="<?php echo get_the_post_thumbnail_url($banner_small[0]->ID, 'full'); ?>" class="w-full h-auto">
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <div class="block lg:hidden">
            <div class="grid grid-cols-2 gap-5 p-5 border">
                <?php if (!empty($banner_large)): ?>
                    <div class="">
                        <a href="<?php the_field('field_5c6325e38e0aa', $banner_large[0]->ID) ?>">
                            <img src="<?php the_field('field_5f0d5b0270f63', $banner_large[0]->ID); ?>" class="w-full h-auto">
                        </a>
                    </div>
                <?php endif; ?>


                <?php if (!empty($banner_small)): ?>
                    <div class="">
                        <a href="<?php the_field('field_5c6325e38e0aa', $banner_small[0]->ID) ?>">
                            <img src="<?php echo get_field('field_5f0d5b0270f63', $banner_small[0]->ID); ?>" class="w-full h-auto">
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
