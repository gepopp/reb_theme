<?php
$today = date('Ymd');
$banner_args = [
    'post_type'      => 'ir_ad',
    'posts_per_page' => 4,
    'tax_query'      => [
        'relation' => 'and',
        [
            'taxonomy'         => 'position',
            'terms'            => 'startseite',
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
$query = new WP_Query($banner_args);
//echo var_dump(count($banners));
?>
<div class="px-5 xl:px-0">
    <div class="container mx-auto mt-20">
        <p class="text-xs text-gray-300"><?php _e('Werbung', 'ir21') ?></p>
        <div class="grid grid-cols-4 gap-5 p-5 border-2  border-primary-100">
            <?php if ($query->have_posts()): ?>
                <?php while ($query->have_posts()): ?>
                    <?php $query->the_post(); ?>

                    <div class="col-span-4 md:col-span-2 lg:col-span-1 flex justify-center">
                        <a href="<?php the_field('field_5c6325e38e0aa') ?>">
                            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(), 'full') ?>" style="width: 300px; max-width:100%; height: auto">
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
