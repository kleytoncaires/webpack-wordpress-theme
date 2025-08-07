<?php


// -----------
// REMOVE JQUERY MIGRATE
// -----------
function remove_jquery_migrate($scripts)
{
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];

        if ($script->deps) { // Check whether the script has any dependencies
            $script->deps = array_diff($script->deps, array(
                'jquery-migrate'
            ));
        }
    }
}

add_action('wp_default_scripts', 'remove_jquery_migrate');

// -----------
// GET PACKAGE VERSION
// -----------
function get_package_version($package) {
    static $package_versions = null;
    
    if ($package_versions === null) {
        $package_json_path = get_template_directory() . '/package.json';
        
        if (file_exists($package_json_path)) {
            $package_json = json_decode(file_get_contents($package_json_path), true);
            $package_versions = $package_json['dependencies'] ?? array();
        } else {
            $package_versions = array();
        }
    }
    
    if (isset($package_versions[$package])) {
        return ltrim($package_versions[$package], '^~');
    }
    
    return null;
}

// -----------
// REPLACE/REMOVE JQUERY VERSION
// -----------
function replace_core_jquery_version()
{
    wp_deregister_script('jquery');
    // Change the URL if you want to load a local copy of jQuery from your own server.
    // wp_register_script('jquery', "https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js", array(), '3.6.0');
}
add_action('wp_enqueue_scripts', 'replace_core_jquery_version');


// -----------
// ENQUEUE SCRIPTS
// -----------
function inc_scripts()
{
    // Obter versões do package.json
    $jquery_version = get_package_version('jquery');
    $bootstrap_version = get_package_version('bootstrap');
    $jquery_mask_version = get_package_version('jquery-mask-plugin');
    $fancybox_version = get_package_version('@fancyapps/ui');
    $swiper_version = get_package_version('swiper');
    $aos_version = get_package_version('aos');

    wp_enqueue_script('jquery', "https://cdn.jsdelivr.net/npm/jquery@{$jquery_version}/dist/jquery.min.js", array(), $jquery_version);
    wp_enqueue_script('bootstrap', "https://cdn.jsdelivr.net/npm/bootstrap@{$bootstrap_version}/dist/js/bootstrap.bundle.min.js", array(), $bootstrap_version, false);
    wp_enqueue_script('jQueryMask', "https://cdn.jsdelivr.net/npm/jquery-mask-plugin@{$jquery_mask_version}/dist/jquery.mask.min.js", array(), $jquery_mask_version, false);
    wp_enqueue_script('fancybox', "https://cdn.jsdelivr.net/npm/@fancyapps/ui@{$fancybox_version}/dist/fancybox/fancybox.umd.min.js", array(), $fancybox_version, false);
    wp_enqueue_script('swiper', "https://cdn.jsdelivr.net/npm/swiper@{$swiper_version}/swiper-bundle.min.js", array(), $swiper_version, false);
    // wp_enqueue_script('aos', "https://cdn.jsdelivr.net/npm/aos@{$aos_version}/dist/aos.min.js", array(), $aos_version, false);
    enqueue_webpack_script('custom', 'scripts.js', array(), true);
    // wp_enqueue_script('cidades', get_template_directory_uri() . '/assets/js/vendor/cidades.js');
}
add_action('wp_enqueue_scripts', 'inc_scripts');
