<?php

// -----------
// DYNAMIC CUSTOM POST TYPE REGISTRATION
// -----------
function dynamic_post_type($singular, $plural, $slug, $supports = array('title', 'editor', 'thumbnail', 'excerpt', 'comments'), $icon = 'dashicons-admin-post', $custom_args = array())
{
    $labels = array(
        'name' => __($plural), // Nome plural
        'singular_name' => __($singular), // Nome singular
        'menu_name' => __($plural), // Nome do menu
        'name_admin_bar' => __($singular), // Nome na barra de administração
        'add_new' => __('Adicionar Novo'), // Adicionar Novo
        'add_new_item' => __('Adicionar Novo ' . $singular), // Adicionar Novo [Nome Singular]
        'new_item' => __('Novo ' . $singular), // Novo [Nome Singular]
        'edit_item' => __('Editar ' . $singular), // Editar [Nome Singular]
        'view_item' => __('Visualizar ' . $singular), // Visualizar [Nome Singular]
        'all_items' => __('Todos os ' . $plural), // Todos os [Nome Plural]
        'search_items' => __('Buscar ' . $plural), // Buscar [Nome Plural]
        'not_found' => __('Nenhum ' . strtolower($singular) . ' encontrado.'), // Nenhum [Nome Singular] encontrado.
        'not_found_in_trash' => __('Nenhum ' . strtolower($singular) . ' encontrado na lixeira.') // Nenhum [Nome Singular] encontrado na lixeira.
    );

    $default_args = array(
        'labels' => $labels, // Custom labels defined earlier
        'public' => true, // Defines if the post type is public and can be displayed in the user interface
        'has_archive' => true, // Allows the creation of an archive for this post type
        'rewrite' => array('slug' => $slug), // Sets the URL structure (slug) for this post type
        'supports' => $supports, // Specifies which features are supported (like title, editor, etc.)
        'show_in_rest' => true, // Enables support for the WordPress block editor (Gutenberg)
        'menu_icon' => $icon, // Sets a custom icon for the admin menu
        'with_front' => false // Determines if the permalink base of the post type should be preceded by the front page permalink base
    );

    // Merge default args with custom args
    $args = array_merge($default_args, $custom_args);

    register_post_type($slug, $args);
}


// -----------
// REGISTER POST TYPES
// -----------
function register_post_types()
{
    // PLACE YOUR POST TYPES HERE
}

add_action('init', 'register_post_types');
