<?php

namespace App\Helpers;

class VideoHelper
{
    /**
     * Convert any YouTube or Vimeo URL (including YouTube Shorts and standard watch links)
     * into an embeddable iframe URL.
     *
     * @param string|null $url
     * @return string|null
     */
    public static function toEmbedUrl(?string $url): ?string
    {
        $url = trim($url ?? '');
        if (empty($url)) {
            return null;
        }

        // 1. YouTube (Watch, Shorts, youtu.be, Embed, Live, Mobile, etc.)
        // Matches standard 11-character YouTube video IDs
        if (preg_match('/(?:youtube(?:-nocookie)?\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?|shorts|live)\/|.*[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/i', $url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        // 2. Vimeo (Direct, Channels, Groups, OnDemand, Player embed)
        if (preg_match('/(?:vimeo\.com\/(?:channels\/(?:\w+\/)?|groups\/[^\/]*\/videos\/|album\/(?:\d+\/)?video\/|video\/|)|player\.vimeo\.com\/video\/)([0-9]+)/i', $url, $matches)) {
            return 'https://player.vimeo.com/video/' . $matches[1];
        }

        // Return original if it is already an HTTPS embed or unrecognized format
        return $url;
    }

    /**
     * Check if a given URL is a valid video/embed URL.
     *
     * @param string|null $url
     * @return bool
     */
    public static function isEmbeddable(?string $url): bool
    {
        return !empty(static::toEmbedUrl($url));
    }
}
