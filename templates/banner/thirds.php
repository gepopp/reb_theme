<?php
$today = date('Ymd');
$banner_small = get_posts([
    'post_type'      => 'ir_ad',
    'posts_per_page' => 1,
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
<div class="container mx-auto mt-32">
    <p class="text-xs text-gray-300"><?php _e('Werbung', 'ir21') ?></p>
    <div class="flex flex-col lg:flex-row">
        <div class="w-full lg:w-1/3 lg:pr-5">
            <a href="<?php the_field('field_5c6325e38e0aa', $banner_small[0]->ID) ?>">
                <img src="<?php echo get_the_post_thumbnail_url($banner_small[0]->ID, 'full'); ?>" class="w-full h-auto">
            </a>
        </div>
        <div class="w-full lg:w-2/3 lg:pl-5 pt-10 lg:pt-0">
            <a href="<?php the_field('field_5c6325e38e0aa', $banner_large[0]->ID) ?>">
                <img src="<?php echo get_the_post_thumbnail_url($banner_large[0]->ID, 'full'); ?>" class="w-full h-auto">
            </a>
        </div>
    </div>
</div>