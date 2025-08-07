<?php

// -----------
// DYNAMIC TAXONOMY REGISTRATION
// -----------

function dynamic_register_taxonomy($taxonomy, $post_types, $custom_args = array(), $label_singular = null, $label_plural = null)
{
    $label_singular = $label_singular ?: ucfirst($taxonomy);
    $label_plural = $label_plural ?: $label_singular . 's';

    $labels = array(
        'name' => $label_plural,
        'singular_name' => $label_singular,
        'menu_name' => $label_plural,
        'all_items' => 'Todas as ' . $label_plural,
        'edit_item' => 'Editar ' . $label_singular,
        'view_item' => 'Ver ' . $label_singular,
        'update_item' => 'Atualizar ' . $label_singular,
        'add_new_item' => 'Adicionar nova ' . $label_singular,
        'new_item_name' => 'Nome da nova ' . $label_singular,
        'search_items' => 'Buscar ' . $label_plural,
        'not_found' => 'Nenhuma ' . $label_singular . ' encontrada',
    );

    $default_args = array(
        'labels' => $labels,
        'public' => true,
        'hierarchical' => true,
        'rewrite' => array('slug' => $taxonomy),
        'show_admin_column' => true,
        'show_in_rest' => true,
    );

    $args = wp_parse_args($custom_args, $default_args);

    register_taxonomy($taxonomy, (array) $post_types, $args);
}

// -----------
// REGISTER TAXONOMIES
// -----------
add_action('init', function () {

    // PLACE YOUR TAXONOMIES HERE

});
