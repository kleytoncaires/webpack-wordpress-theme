<?php

// -----------
// ADD THEME SUPPORT
// -----------
add_theme_support('title-tag');
add_theme_support('post-thumbnails');
// add_image_size('sidebar-thumb', 120, 120, true); // Hard Crop Mode
// add_image_size('homepage-thumb', 220, 180); // Soft Crop Mode
// add_image_size('singlepost-thumb', 590, 9999); // Unlimited Height Mode

// -----------
// REMOVES WIDTH AND HEIGHT ATTRIBUTES FROM IMAGE TAGS
// -----------
// function remove_image_size_attributes($html)
// {
//     return preg_replace('/(width|height)="\d*"/', '', $html);
// }

// -----------
// REMOVE IMAGE SIZE ATTRIBUTES FROM POST THUMBNAILS
// -----------
// add_filter('post_thumbnail_html', 'remove_image_size_attributes');

// -----------
// REMOVE IMAGE SIZE ATTRIBUTES FROM IMAGES ADDED TO A WORDPRESS POST
// -----------
// add_filter('image_send_to_editor', 'remove_image_size_attributes');

// -----------
// REMOVE IMAGE SIZE ATTRIBUTES FROM CAPTIONIZED IMAGES ADDED TO A WORDPRESS POST
// -----------
// add_filter('img_caption_shortcode_width', '__return_false');
