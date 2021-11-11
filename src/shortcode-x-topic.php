<?php
add_shortcode('xtopic', function($args, $content){
$defaults = [
    "title"     => "",
    "ids"       => false,
    "alignment" => 'left',
];

$atts = shortcode_atts($defaults, $args);
$ids = explode(',', $atts['ids']);
ob_start();

    foreach ($ids as $id):
?>


    <div class="w-full bg-primary-100 bg-opacity-50 p-5 my-10 flex flex-col lg:flex-row lg:space-x-4">
        <div class="w-full lg:w-1/4">
            <img src="<?php echo get_the_post_thumbnail_url($id, 'small') ?>" class="w-full h-auto"/>
        </div>
        <div class="text-white w-full lg:w-3/4 pt-5 lg:pt-0">
            <p class="text-sm"><?php echo $atts['title'] ?></p>
            <h1 class="text-2xl font-serif"><?php echo get_the_title($id) ?></h1>
            <p><?php echo get_the_excerpt($id) ?></p>
            <div class="flex justify-end mt-5 mb-2">
                <a href="<?php echo get_the_permalink($id) ?>" class="bg-white text-primary-100 p-3 shadow-xl">weiterlesen</a>
            </div>
        </div>
    </div>



<?php

    endforeach;

    return ob_get_clean();
});