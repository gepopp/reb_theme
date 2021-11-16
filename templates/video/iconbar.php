<?php
$default = [
    'mode' => 'dark',
];
/**
 * @var $args []
 * @var $mode String
 */
extract(array_merge($default, $args));
?>


<div x-data="readingFunctions(<?php echo get_current_user_id() ?>)"
     x-init="load()"
     x-cloak>
    <div class="relative lg:flex hidden">
        <div class="flex justify-center items-center p-2 hover:shadow-none">
            <a href="https://twitter.com/REB_Institute" target="_blank" class="w-4 h-4">
	            <?php get_template_part('icons','twitter',  ['color' => 'black']) ?>
            </a>
        </div>

        <div class="flex justify-center items-center p-2 hover:shadow-none">
            <a href="https://www.linkedin.com/in/rebinstitute/" target="_blank" class="w-4 h-4">
	            <?php get_template_part('icons','linkedin',  ['color' => 'black']) ?>
            </a>
        </div>
    </div>
</div>
