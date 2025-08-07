<?php

// -----------
// ACF local JSON
// -----------
add_filter('acf/settings/load_json', 'my_acf_json_load_point');
function my_acf_json_load_point($paths)
{
    unset($paths[0]);
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
}

// -----------
// OPTIONS PAGE
// -----------

if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title'    => 'Central de Informações',
        'menu_title'    => 'Central de Informações',
        'menu_slug'     => 'about',
        'capability'    => 'edit_posts',
        'redirect'      => true,
        'icon_url'      => 'dashicons-admin-site',
        'position'      => 2
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Cabeçalho',
        'menu_title'    => 'Cabeçalho',
        'parent_slug'   => 'about',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Rodapé',
        'menu_title'    => 'Rodapé',
        'parent_slug'   => 'about',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));

    acf_add_options_sub_page(array(
        'page_title'    => 'Redes Sociais',
        'menu_title'    => 'Redes Sociais',
        'parent_slug'   => 'about',
        'capability'    => 'edit_posts',
        'redirect'      => false
    ));
}
