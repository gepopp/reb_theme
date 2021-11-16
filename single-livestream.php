<?php
get_header();
the_post();

$cats = get_terms( 'immolive' );

get_template_part( 'content', 'live' );

get_footer();
