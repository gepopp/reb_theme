<?php
get_header('livestream');
the_post();

$cats = get_terms( 'immolive' );

get_template_part( 'content', 'live' );

get_footer();
