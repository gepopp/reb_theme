<?php
if ( empty( $args['size'] ) ) {
	$font_size = 'text-xl lg:text-3xl';
} else {
	$font_size = '';
}
?>
<div class="absolute top-0 left-0 w-full h-full flex items-end bg-gradient-to-b from-transparent to-gray-900 p-5">
    <div>
        <h2 class="text-white font-serif <?php echo $font_size ?> leading-tight w-full line-clamp-2 overflow-hidden">
			<?php echo get_the_title() ?>
        </h2>
    </div>
</div>

