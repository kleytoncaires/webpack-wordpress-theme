<?php

// -----------
// AJAX TO LOAD MORE POSTS WITHOUT RELOADING
// -----------
function load_more_posts()
{
    $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'post';
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $posts_per_page = isset($_POST['posts_per_page']) ? intval($_POST['posts_per_page']) : 4;

    $args = array(
        'post_status'    => 'publish',
        'post_type'      => $post_type,
        'posts_per_page' => $posts_per_page,
        'orderby'        => 'post_date',
        'order'          => 'desc',
        'paged'          => $paged,
    );

    $posts_query = new WP_Query($args);

    if ($posts_query->have_posts()) {
        while ($posts_query->have_posts()) : $posts_query->the_post();
            get_template_part('partials/loop', $post_type);
        endwhile;
    }

    wp_reset_postdata();
    wp_die();
}
add_action('wp_ajax_load_more_posts', 'load_more_posts');
add_action('wp_ajax_nopriv_load_more_posts', 'load_more_posts');

// -----------
// AJAX TO SEARCH POSTS
// -----------
function ajax_search_posts()
{
    if (isset($_POST['query']) && isset($_POST['post_type']) && isset($_POST['posts_per_page'])) {
        $query = sanitize_text_field($_POST['query']);
        $post_type = sanitize_text_field($_POST['post_type']);
        $posts_per_page = intval($_POST['posts_per_page']);

        $args = [
            's' => $query,
            'post_type' => $post_type,
            'posts_per_page' => $posts_per_page
        ];

        $search_query = new WP_Query($args);

        if ($search_query->have_posts()) {
            echo '<ul>';
            while ($search_query->have_posts()) {
                $search_query->the_post();
                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            }
            echo '</ul>';
        } else {
            echo '<p>Nenhum resultado encontrado.</p>';
        }

        wp_reset_postdata();
    }

    wp_die();
}
add_action('wp_ajax_search_posts', 'ajax_search_posts');
add_action('wp_ajax_nopriv_search_posts', 'ajax_search_posts');
