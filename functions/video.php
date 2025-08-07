<?php

// -----------
// GET VIDEO THUMBNAILS
// -----------
function get_video_thumbnail($video_link)
{
    if (strpos($video_link, 'vimeo.com') !== false) {
        $link_parts = explode("/", parse_url($video_link, PHP_URL_PATH));
        $video_id = end($link_parts);

        $json_data = file_get_contents("https://vimeo.com/api/v2/video/" . $video_id . ".json");
        $video_data = json_decode($json_data, true);

        return $video_data[0]['thumbnail_large'];
    } elseif (strpos($video_link, 'youtube.com') !== false || strpos($video_link, 'youtu.be') !== false) {
        // YouTube normal: https://www.youtube.com/watch?v=VIDEO_ID
        if (strpos($video_link, 'youtube.com') !== false) {
            parse_str(parse_url($video_link, PHP_URL_QUERY), $params);
            $video_id = $params['v'] ?? '';
        }
        // YouTube encurtado: https://youtu.be/VIDEO_ID ou https://youtu.be/VIDEO_ID?feature=shared
        elseif (strpos($video_link, 'youtu.be') !== false) {
            $path = parse_url($video_link, PHP_URL_PATH);
            $video_id = trim($path, '/');
        }

        if (!empty($video_id)) {
            return "https://img.youtube.com/vi/" . $video_id . "/hqdefault.jpg";
        }
    } else {
        return '';
    }
}

// -----------
// GET VIDEO IDS
// -----------

function get_youtube_video_id($url)
{
    $query_string = parse_url($url, PHP_URL_QUERY);
    parse_str($query_string, $params);
    if (isset($params['v'])) {
        return $params['v'];
    }
    return false;
}

function get_vimeo_video_id($url)
{
    preg_match('/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/', $url, $matches);
    if (isset($matches[2])) {
        return $matches[2];
    }
    return false;
}
