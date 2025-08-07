<?php

// -----------
// ADD EXCERPT TO PAGES
// -----------
function add_excerpt_to_pages()
{
    add_post_type_support('page', 'excerpt');
}
add_action('init', 'add_excerpt_to_pages');

// -----------
// MODIFY EXCERPT LENGTH
// -----------
function custom_excerpt_length($length)
{
    return 25;
}
add_filter('excerpt_length', 'custom_excerpt_length', 999);

// -----------
// CHANGE MORE EXCERPT
// -----------
// function custom_more_excerpt($more)
// {
//     return '...';
// }
// add_filter('excerpt_more', 'custom_more_excerpt');

// -----------
// REMOVE STRING WHITESPACE
// -----------
function clean($string)
{
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

    return preg_replace('/[^a-zA-Z0-9]+/', '', $string); // Removes special chars.
}

// ----------- 
// ADD CLASSES IN MENU ITEMS
// -----------
function add_menu_link_class($atts, $item, $args)
{
    if (property_exists($args, 'link_class')) {
        $atts['class'] = $args->link_class;
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'add_menu_link_class', 1, 3);

function add_menu_list_item_class($classes, $item, $args)
{
    if (property_exists($args, 'list_item_class')) {
        $classes[] = $args->list_item_class;
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'add_menu_list_item_class', 1, 3);


// ----------- 
// REMOVE AUTHOR IN SHARE
// -----------
add_filter('oembed_response_data', 'remover_author_compartilhamento');
function remover_author_compartilhamento($data)
{
    unset($data['author_url']);
    unset($data['author_name']);
    return $data;
}
