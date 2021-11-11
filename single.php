<?php
get_header();
the_post();


$cats = wp_get_post_categories(get_the_ID());

if(!empty(array_filter($cats, function ($cat){
    $filter = get_field('field_60733fe611fac', 'option');
    $filter = is_array($filter) ? $filter : [];

    if(in_array($cat, $filter )){
        return $cat;
    }
})) && get_post_format() !== 'video'){
    get_template_part('content', 'live');
}elseif (!get_post_format()) {
    get_template_part('content', 'article');
} elseif (get_post_format() == 'video') {
    get_template_part('content', 'video');
}



get_footer();