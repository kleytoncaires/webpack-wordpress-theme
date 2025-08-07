<?php

// -----------
// MOVE YOAST TO THE BOTTOM IN ADMIN
// -----------
function yoasttobottom()
{
    return 'low';
}
add_filter('wpseo_metabox_prio', 'yoasttobottom');

// -----------
// YOAST SOCIAL LINKS
// -----------

/**
 * Social Links
 * Uses Social URLs specified in Yoast SEO. See SEO > Social
 *
 */
function ea_social_links() {
    $options = array(
        'facebook' => array(
            'key'  => 'facebook_site',
            'icon' => '<i class="icon-facebook"></i>',
        ),
        'twitter' => array(
            'key'     => 'twitter_site',
            'prepend' => 'https://twitter.com/',
            'icon'    => '<i class="icon-twitter"></i>',
        ),
    );

    $options = apply_filters('ea_social_link_options', $options);

    // Mapeamento de ícones para other_social_urls
    $icon_mapping = array(
        'whatsapp'  => '<i class="icon-whatsapp"></i>',
        'linkedin'  => '<i class="icon-linkedin-in"></i>',
        'instagram' => '<i class="icon-instagram"></i>',
        'youtube'   => '<i class="icon-youtube"></i>',
        'tiktok'    => '<i class="icon-tiktok"></i>',
    );

    $icon_mapping = apply_filters('ea_social_icon_mapping', $icon_mapping);

    $output = array();
    $seo_data = get_option('wpseo_social');

    if (!is_array($seo_data)) {
        return '';
    }

    // Processa opções configuradas
    foreach ($options as $social => $settings) {
        $url = !empty($seo_data[$settings['key']]) ? $seo_data[$settings['key']] : false;

        if (!empty($url) && !empty($settings['prepend'])) {
            $url = $settings['prepend'] . $url;
        }

        if ($url) {
            $output[] = ea_create_social_link($url, $social, $settings['icon'] ?? '');
        }
    }

    // Processa other_social_urls se existir
    if (!empty($seo_data['other_social_urls']) && is_array($seo_data['other_social_urls'])) {
        foreach ($seo_data['other_social_urls'] as $social => $url) {
            if (empty($url)) continue;

            $icon = '';
            foreach ($icon_mapping as $platform => $platform_icon) {
                if (stripos($url, $platform) !== false) {
                    $icon = $platform_icon;
                    break;
                }
            }

            $output[] = ea_create_social_link($url, $social, $icon);
        }
    }

    return !empty($output) ? '<div class="social-links">' . implode(' ', $output) . '</div>' : '';
}

function ea_create_social_link($url, $social, $icon = '') {
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return '';
    }

    $content = !empty($icon) ? $icon . '<span class="visually-hidden">' . esc_html($social) . '</span>' : esc_html($social);
    
    return '<a href="' . esc_url($url) . '" target="_blank" rel="noopener">' . $content . '</a>';
}

add_shortcode('social_links', 'ea_social_links');
