<?php

// -----------
// LOAD DASHICONS
// -----------
// function ww_load_dashicons()
// {
//     wp_enqueue_style('dashicons');
// }
// add_action('wp_enqueue_scripts', 'ww_load_dashicons');

// -----------
// REMOVE DASHICONS
// -----------
function wpdocs_dequeue_dashicon()
{
    if (current_user_can('update_core')) {
        return;
    }
    wp_deregister_style('dashicons');
}
add_action('wp_enqueue_scripts', 'wpdocs_dequeue_dashicon');


// -----------
// REMOVE WP EMOJI
// -----------
function disable_wp_emojicons()
{
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    add_filter('tiny_mce_plugins', 'disable_emojicons_tinymce');
    add_filter('emoji_svg_url', '__return_false');
}
add_action('init', 'disable_wp_emojicons');

function disable_emojicons_tinymce($plugins)
{
    return is_array($plugins) ? array_diff($plugins, array('wpemoji')) : array();
}

// -----------
// DISABLE BLOCK LIBRARY
// -----------
// function wpassist_remove_block_library_css()
// {
//     wp_dequeue_style('wp-block-library');
// }
// add_action('wp_enqueue_scripts', 'wpassist_remove_block_library_css');

// ----------- 
// ADD PAGE SLUG TO BODY CLASS
// -----------
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// ----------- 
// ENQUEUE STYLES
// -----------
function inc_styles()
{
    enqueue_webpack_style('main-style', 'style.css', array(), 'all');
}
add_action('wp_enqueue_scripts', 'inc_styles');
