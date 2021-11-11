<?php
$defaults = [
  'posts' => 3,
];




$search = in_category(159) ? get_the_title() : false;

if ($search && get_field('field_5fa63966c0b24')) {

    $search .= '+' . get_field('field_5fa63966c0b24');

} elseif (get_field('field_5fa63966c0b24')) {

    $search = get_field('field_5fa63966c0b24');

} else {

    // Titel holen
    $title = $s = str_replace(['?', '!', '.', ':'], ' ', html_entity_decode(get_the_title()));

    // Wörter trennen
    $word = explode(' ', $title);

    // filter nur Würter mit mehr als 3 Buchstaben
    $words = array_filter($word, function ($w) {
        if (strlen(htmlspecialchars_decode($w)) > 3) {
            return $w;
        }
    });

    // Durcheinander bringen - random
    shuffle($words);

    // auf 5 Wörter kürzen
    $words = array_chunk($words, 3);

    // Suche zusammenbauen
    $search = implode('+', $words[0]);

}


if ($search != '+'): ?>
    <?php

    $query = new WP_Query(
        [
            'post_type'           => 'post',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
            'posts_per_page'      => 3,
            's'                   => $search,
            'post__not_in'        => [get_the_ID()],
            'orderby'             => 'rand',
        ]
    );

    ?>


    <?php if ($query->have_posts()): ?>
        <div class="container mx-auto mt-20 flex justify-center items-center">
            <div class="grid grid-cols-5 gap-4 w-full">
                <div class="hidden lg:block"></div>
                <?php while ($query->have_posts()): ?>
                    <?php $query->the_post(); ?>
                    <div class="col-span-5 lg:col-span-1 p-5 lg:p-0">
                        <div class="relative w-full">
                            <a href="<?php the_permalink(); ?>">
                                <div class="image-holder w-full h-full" style="padding-top: 56.25%;">
                                    <?php the_post_thumbnail('featured_small', ['class' => 'w-full h-auto max-w-full', 'style' => 'margin-top: -56.25%']); ?>
                                </div>
                            </a>
                        </div>
                        <p class="mt-5 font-semibold text-xs pb-5">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_title() ?>
                            </a>
                        </p>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endif; ?>
    <?php wp_reset_postdata(); ?>
<?php endif; ?>