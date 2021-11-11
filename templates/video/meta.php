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

<div class="text-white flex justify-between w-full">
    <div>
        <?php get_template_part('video', 'iconbar', ['mode' => $mode]) ?>
    </div>
    <div class="flex mt-2">
        <div class="flex" x-data="readTime('<?php echo preg_replace("/[^ A-Za-z0-9?!]/", '', str_replace('"', '', wp_strip_all_tags(get_the_content()))); ?>')">
            <svg class="mr-3 w-4 h-4 <?php echo $mode == 'dark' ? 'text-white' : 'text-gray-800' ?> inline" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
            </svg>
            <p class="<?php echo $mode == 'dark' ? 'text-white' : 'text-gray-800' ?> text-xs" x-text="minutes"></p>
        </div>

        <div class="flex text-white mx-5">
            <svg class="w-4 h-4 <?php echo $mode == 'dark' ? 'text-white' : 'text-gray-800' ?> inline mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
            </svg>
            <p class="<?php echo $mode == 'dark' ? 'text-white' : 'text-gray-800' ?> text-xs"><?php the_time('d.m.Y') ?></p>
        </div>

        <div class="flex text-white" x-data="articleViews(<?php the_ID(); ?>)" x-init="viewsXHR()">
            <svg class="w-4 h-4 <?php echo $mode == 'dark' ? 'text-white' : 'text-gray-800' ?> mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
            </svg>
            <p>
                <svg x-show="!views" class="w-6 h-6 text-gray-300 animate-pulse" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"></path>
                </svg>
            <p class="<?php echo $mode == 'dark' ? 'text-white' : 'text-gray-800' ?> text-xs" x-show="views" x-text="views"></p>
            </p>
        </div>
    </div>
</div>
<hr class="my-2">
