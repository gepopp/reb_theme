<?php
$cat = wp_get_post_categories(get_the_ID(), ['child_of' => 17]);

if (empty($cat)) {
    $cat = get_category(17);
} else {
    $cat = array_shift($cat);
    $cat = get_category($cat);
}
?>

<div class="hidden lg:block col-span-2">
    <script>
        function data() {
            return {
                scrolled: 0,
            }
        }
    </script>
    <div class="relative h-64 hidden lg:block" id="powered"
         x-data="data()"
         @scroll.window="scrolled = document.getElementById('powered').offsetTop - window.pageYOffset">
        <div class="absolute w-full h-full" :style="`top: ${ scrolled < 0 ? (scrolled * -1) + 100 : 0 }px;`">
            <div class="h-full" style="background-color: <?php the_field('field_5c63ff4b7a5fb', $cat); ?>">
                <div id="scrollspy" class="flex flex-col justify-between h-full">
                    <p class="p-5 font-serif text-2xl text-white"><?php echo $cat->name ?></p>
                </div>
                <?php if (get_field('field_5f9aeff4efa16', $cat)): ?>
                    <div class="z-10 absolute bottom-0 right-0 -mb-5 -mr-5 bg-white rounded-full w-24 h-24 flex flex-col items-center justify-center shadow-lg">
                        <a href="<?php echo get_field('field_5f9aeff4efa16', $cat) ?>" class="text-center">
                            <p class="text-xs text-gray-900">powered by</p>
                            <img src="<?php echo get_field('field_5f9aefd116e2e', $cat) ?>" class="w-24 h-auto px-5">
                        </a>
                    </div>
                <?php endif; ?>

                <?php
                $next = get_posts([
                    'post_type'           => 'post',
                    'post_status'         => 'publish',
                    'ignore_sticky_posts' => true,
                    'posts_per_page'      => 1,
                    'category__in'        => [17],
                    'date_query'          => [
                        [
                            'before' => get_the_time('d-m-Y H:i:s'),
                        ],
                    ],
                ]);
                $next_id = $next[0]->ID;
                ?>
                <div class="relative">
                    <a href="<?php echo get_the_permalink($next_id); ?>">
                        <?php if (get_field('field_5c65130772844', $next_id)): ?>
                            <img class="w-full h-auto" src="https://cdn.jwplayer.com/v2/media/<?php echo get_field('field_5c65130772844', $next_id) ?>/poster.jpg"/>
                        <?php elseif (get_field('field_5f96fa1673bac', $next_id)): ?>
                            <img class="w-full h-auto" src="https://img.youtube.com/vi/<?php echo get_field('field_5f96fa1673bac', $next_id) ?>/mqdefault.jpg"/>
                        <?php elseif (get_field('field_5fe2884da38a5', $next_id)): ?>
                            <?php
                            $lib = new \Vimeo\Vimeo('f1663d720a1da170d55271713cc579a3e15d5d2f', 'd30MDbbXFXRhZK2xlnyx5VMk602G7J8Z0VHFP8MvNnDDuAVfcgPj2t5zwE5jpbyXweFrQKa9Ey02edIx/E3lJNVqsFxx+9PRShAkUA+pwyCeoh9rMoVT2dWv2X7WurgV', 'b57bb7953cc356e8e1c3ec8d4e17d2e9');
                            $response = $lib->request('/videos/' . get_field('field_5fe2884da38a5', $next_id), [], 'GET');
                            $body = $response['body'];
                            ?>
                            <img src="<?php echo $body['pictures']['sizes'][3]['link'] ?>">
                        <?php endif; ?>
                        <div class="absolute w-full h-full flex flex-col justify-end top-0 left-0 p-5">
                            <div class="inline ">
                                                <span class="bg-white text-gray-900 text-sm py-2 px-3 font-bold">
                                                    <?php _e('Nächster Clip', 'ir21') ?>
                                                </span>
                            </div>
                            <h1 class="font-serif text-white text-xl mt-5"><?php echo get_the_title($next_id) ?></h1>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>