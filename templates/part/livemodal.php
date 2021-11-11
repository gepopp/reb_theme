<?php
$date_now = date( 'Y-m-d H:i:s' );
$query    = new WP_Query( [
	'post_type'      => 'immolive',
	'posts_per_page' => -1,
	'meta_query'     => [
		[
			'key'     => 'termin',
			'compare' => '<=',
			'value'   => $date_now,
			'type'    => 'DATETIME',
		],
	],
	'meta_key'       => 'termin',
	'orderby'        => 'meta_value',
	'order'          => 'DESC',
]);

if($query->have_posts()){
	while ($query->have_posts()){
		$query->the_post();

		$termin = get_field('field_5ed527e9c2279');
		




	}
}


?>

