<?php

// ----------- 
// REMOVE DASHBOARD WIDGETS 
// -----------
remove_action('welcome_panel', 'wp_welcome_panel'); //remove WordPress Welcome Panel
add_action('admin_init', 'remove_dashboard_widgets');
function remove_dashboard_widgets()
{
    remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // At a Glance
    remove_meta_box('dashboard_quick_press', 'dashboard', 'normal'); // Quick Draft
    remove_meta_box('dashboard_primary', 'dashboard', 'core'); // WordPress News
    remove_meta_box('dashboard_secondary', 'dashboard', 'core'); // WordPress News
    remove_meta_box('dashboard_activity', 'dashboard', 'normal'); // Activity
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'core'); // Comments Widget
    remove_meta_box('dashboard_recent_drafts', 'dashboard', 'core'); // Drafts Widget
    remove_meta_box('dashboard_incoming_links', 'dashboard', 'core'); // Incoming Links Widget
    remove_meta_box('dashboard_plugins', 'dashboard', 'core'); // Plugins Widget

    if (!is_admin()) {
        wp_deregister_script('jquery');                                     // De-Register jQuery
        wp_register_script('jquery', '', '', '', true);                     // Register as 'empty', because we manually insert our script in header.php
    }
}

// ----------- 
// MANAGE MENU 
// -----------
function remove_menus()
{

    //remove_menu_page( 'index.php' );                  //Dashboard
    //remove_menu_page( 'jetpack' );                    //Jetpack* 
    //remove_menu_page( 'upload.php' );                 //Media
    //remove_menu_page( 'edit.php?post_type=page' );    //Pages
    //remove_menu_page( 'themes.php' );                 //Appearance
    //remove_menu_page( 'plugins.php' );                //Plugins
    //remove_menu_page( 'users.php' );                  //Users
    //remove_menu_page( 'tools.php' );                  //Tools
    //remove_menu_page( 'options-general.php' );        //Settings
    //remove_menu_page('edit.php');                     //Posts
    remove_menu_page('edit-comments.php');          //Comments

}
add_action('admin_menu', 'remove_menus');


// ----------- 
// DISABLE SELF PINGBACKS
// -----------
function wpsites_disable_self_pingbacks(&$links)
{
    foreach ($links as $l => $link)
        if (0 === strpos($link, get_option('home')))
            unset($links[$l]);
}

add_action('pre_ping', 'wpsites_disable_self_pingbacks');

// ----------- 
// DISABLE FEED
// -----------
function itsme_disable_feed()
{
    wp_die(__('No feed available, please visit the homepage!'));
}

add_action('do_feed', 'itsme_disable_feed', 1);
add_action('do_feed_rdf', 'itsme_disable_feed', 1);
add_action('do_feed_rss', 'itsme_disable_feed', 1);
add_action('do_feed_rss2', 'itsme_disable_feed', 1);
add_action('do_feed_atom', 'itsme_disable_feed', 1);
add_action('do_feed_rss2_comments', 'itsme_disable_feed', 1);
add_action('do_feed_atom_comments', 'itsme_disable_feed', 1);

// ----------- 
// DISPLAY POST EXCERPT BY DEFAULT
// -----------
function wpse_edit_post_show_excerpt()
{
    $user = wp_get_current_user();
    $unchecked = get_user_meta($user->ID, 'metaboxhidden_post', true);
    if (!empty($unchecked)) {
        $key = array_search('postexcerpt', $unchecked);
        if (FALSE !== $key) {
            array_splice($unchecked, $key, 1);
            update_user_meta($user->ID, 'metaboxhidden_post', $unchecked);
        }
    }
}
add_action('admin_init', 'wpse_edit_post_show_excerpt', 10);

function show_excerpt_meta_box($hidden, $screen)
{
    if ('post' == $screen->base) {
        foreach ($hidden as $key => $value) {
            if ('postexcerpt' == $value) {
                unset($hidden[$key]);
                break;
            }
        }
    }
    return $hidden;
}
add_filter('default_hidden_meta_boxes', 'show_excerpt_meta_box', 10, 2);


// -----------
// SVG SUPPORT
// -----------
function allow_svg_upload($mimes)
{
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'allow_svg_upload');

function svg_thumbnail_support()
{
    if (function_exists('add_theme_support')) {
        add_theme_support('post-thumbnails');
        add_filter('wp_generate_attachment_metadata', 'svg_attachment_metadata', 10, 2);
    }
}

function svg_attachment_metadata($metadata, $attachment_id)
{
    $attachment = get_post($attachment_id);
    $mime_type  = get_post_mime_type($attachment);

    if ('image/svg+xml' === $mime_type) {
        $metadata['width']  = 0;
        $metadata['height'] = 0;
    }

    return $metadata;
}

add_action('after_setup_theme', 'svg_thumbnail_support');


// ----------- 
// REMOVE WORDPRESS JUNKS 
// -----------
remove_action('wp_head', 'wp_generator');     //  wordpress version from header
remove_action('wp_head', 'rsd_link');       // link to Really Simple Discovery service endpoint
remove_action('wp_head', 'wlwmanifest_link');   // link to Windows Live Writer manifest file
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'start_post_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('template_redirect', 'wp_shortlink_header', 11); // Remove WordPress Shortlinks from HTTP Headers

// ----------- 
// CUSTOM ADMIN LOGO
// -----------
function my_login_logo()
{ ?>
    <style type="text/css">
        #login,
        .login {
            background-color: #000;
        }

        #login h1 a,
        .login h1 a {
            background-image: url(<?php echo get_template_directory_uri(); ?>/assets/img/wp-login.png);
            background-size: 320px 240px;
            background-repeat: no-repeat;
            height: 240px;
            padding-bottom: 30px;
            width: 320px;
        }

        .login #backtoblog a,
        .login #nav a {
            color: #fff !important;
        }
    </style>
<?php }
add_action('login_enqueue_scripts', 'my_login_logo');

// ----------- 
// INCLUDE NAVIGATION MENUS
// -----------
function register_my_menus()
{
    register_nav_menus(
        array(
            'header-nav' => __('Menu Principal'),
            'footer-nav' => __('Menu do Rodapé'),
        )
    );
}
add_action('init', 'register_my_menus');
