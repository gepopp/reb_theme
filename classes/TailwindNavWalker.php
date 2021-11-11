<?php


namespace reb_livestream_classes;


class TailwindNavWalker extends \Walker_Nav_Menu
{


    private $curItem;


    function start_el(&$output, $item, $depth = 0, $args = [], $id = 0)
    {

        $this->curItem = $item;

        $object = $item->object;

// wp_die(var_dump($item));


        $type = $item->type;
        $title = $item->title;
        $description = $item->description;
        $permalink = $item->url ?? '';

        if ($depth == 0) {
            $class = 'text-white uppercase';
        } else {
            $class = 'text-lg font-bold flex items-center space-x-3 hover:underline';
        }


        $output .= '<li class=""';
        if ($args->walker->has_children ?? false) {
            $output .= ' @mouseenter="open = ' . $item->ID . '"';
        }
        $output .= '>';

        if ($permalink && $permalink != '#') {
            $output .= '<a href="' . $permalink . '" class="' . $class . '"';

			$color = '';
            if ($item->object == 'category') {
                $color = get_field('field_5c63ff4b7a5fb', get_category($item->object_id));
            }

            if($item->object == 'aktuelle_presse'){
            	$color = get_field('field_613b5990f3543', 'option');
            }

	        if($item->object == 'zur_person'){
		        $color = get_field('field_613b878f77b81', 'option');
	        }

	        if($item->object == 'immobilien_projekt'){
		        $color = get_field('field_613b8693e9b81', 'option');
	        }

	        $output .= ' style="background: linear-gradient(0deg, ' . $color . ' 0%, ' . $color . ' 50%, transparent 50%, transparent 100%);"';


            $output .= '>';
        } else {
            $output .= '<span class="' . $class . '">';
        }

        $output .= $title;

        if ($permalink && $permalink != '#') {
            $output .= '</a>';
        } else {
            $output .= '</span>';
        }

    }


    function end_el(&$output, $item, $depth = 0, $args = null)
    {
        $output .= '</li>';
    }

    function start_lvl(&$output, $depth = 0, $args = null)
    {
        $output .= '<div class="absolute mt-2 p-5 z-50 shadow-lg bg-white w-64 text-black" x-show="open == ' . $this->curItem->ID . '" @mouseleave="open = false" x-cloak><ul>';
    }

    function end_lvl(&$output, $depth = 0, $args = null)
    {
        $output .= '</ul></div>';
    }

}
