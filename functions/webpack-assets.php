<?php

// -----------
// WEBPACK ASSETS HELPERS
// -----------

/**
 * Get webpack asset from manifest
 */
function get_webpack_asset($asset_name) {
    static $manifest = null;
    static $error_logged = false;
    
    if ($manifest === null) {
        $manifest_path = get_template_directory() . '/asset-manifest.json';
        if (file_exists($manifest_path)) {
            $manifest_content = file_get_contents($manifest_path);
            $manifest = json_decode($manifest_content, true);
            
            if (json_last_error() !== JSON_ERROR_NONE && !$error_logged) {
                error_log('Webpack Assets: Invalid JSON in asset-manifest.json - ' . json_last_error_msg());
                $error_logged = true;
            }
        } else {
            $manifest = false;
            if (!$error_logged) {
                error_log('Webpack Assets: asset-manifest.json not found. Run webpack build first.');
                $error_logged = true;
            }
        }
    }
    
    if (!$manifest || !isset($manifest['files'])) {
        if (!$error_logged) {
            error_log("Webpack Assets: Using fallback for '{$asset_name}' - manifest not available");
            $error_logged = true;
        }
        return $asset_name; // Fallback to original name
    }
    
    // Para CSS
    if ($asset_name === 'style.css') {
        foreach ($manifest['files'] as $key => $value) {
            if (preg_match('/^style\.[a-f0-9]{8}\.css$/', $key)) {
                return $key;
            }
        }
        // Se não encontrou CSS com hash, pode ser que a compilação falhou
        if (!$error_logged) {
            error_log("Webpack Assets: No CSS file found in manifest. Check webpack compilation.");
            $error_logged = true;
        }
    }
    
    // Para JS  
    if ($asset_name === 'scripts.js') {
        foreach ($manifest['files'] as $key => $value) {
            if (preg_match('/^scripts\.[a-f0-9]{8}\.js$/', $key)) {
                return $key;
            }
        }
    }
    
    return $asset_name; // Fallback
}

/**
 * Enqueue webpack CSS with hash
 */
function enqueue_webpack_style($handle, $asset_name, $deps = array(), $media = 'all') {
    $hashed_asset = get_webpack_asset($asset_name);
    wp_enqueue_style($handle, get_template_directory_uri() . '/' . $hashed_asset, $deps, null, $media);
}

/**
 * Enqueue webpack script with hash
 */
function enqueue_webpack_script($handle, $asset_name, $deps = array(), $in_footer = false) {
    $hashed_asset = get_webpack_asset($asset_name);
    wp_enqueue_script($handle, get_template_directory_uri() . '/' . $hashed_asset, $deps, null, $in_footer);
}