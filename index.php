<?php

function fetch_youtube_rss($channel_url) {
    $rss = @simplexml_load_file($channel_url);

    if (!$rss) {
        die("RSS feed load nahi ho rahi. Link check karein.");
    }

    $rss_feed = '<?xml version="1.0" encoding="UTF-8" ?>';
    $rss_feed .= '<rss version="2.0"><channel>';
    $rss_feed .= '<title>' . htmlspecialchars($rss->title) . '</title>';
    $rss_feed .= '<link>' . htmlspecialchars($rss->link) . '</link>';
    $rss_feed .= '<description>Custom RSS Feed for YouTube Channel</description>';

    foreach ($rss->entry as $entry) {
        $title = (string) $entry->title;
        $link = (string) $entry->link['href'];
        $description = strip_tags((string) $entry->content);

        $video_id = get_youtube_video_id($link);
        $thumbnail_url = "https://img.youtube.com/vi/$video_id/maxresdefault.jpg";

        $rss_feed .= '<item>';
        $rss_feed .= '<title>' . htmlspecialchars($title) . '</title>';
        $rss_feed .= '<link>' . htmlspecialchars($link) . '</link>';
        $rss_feed .= '<description><![CDATA[';
        $rss_feed .= "<img src='$thumbnail_url' style='max-width: 100%;'><br>";
        $rss_feed .= $description;
        $rss_feed .= ']]></description>';
        $rss_feed .= '</item>';
    }

    $rss_feed .= '</channel></rss>';

    file_put_contents('custom_rss_feed.xml', $rss_feed);
    echo "RSS feed ban gaya! 🚀 <a href='custom_rss_feed.xml'>Feed dekhein</a>";
}

function get_youtube_video_id($url) {
    preg_match('/v=([a-zA-Z0-9_-]{11})/', $url, $matches);
    return $matches[1] ?? null;
}

$channel_link = 'https://www.youtube.com/feeds/videos.xml?channel_id=UCWOA1ZGywLbqmigxE4Qlvuw';
fetch_youtube_rss($channel_link);

?>
