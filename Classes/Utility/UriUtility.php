<?php

namespace P2media\Httpmonitoring\Utility;

use TYPO3\CMS\Core\Utility\HttpUtility;

class UriUtility
{
    /**
     * Reduces a URI to the host and path parts
     */
    public static function trimUri(string $uri): string|false
    {
        $parsedUri = parse_url($uri);
        if ($parsedUri === false) {
            return false;
        }

        $normalizedUri = [];
        if (array_key_exists('host', $parsedUri)) {
            if (!preg_match('#^[a-z0-9.\-]*$#i', $parsedUri['host'])) {
                $host = idn_to_ascii($parsedUri['host']);
                $parsedUri['host'] = $host;
            }

            $normalizedUri['host'] = $parsedUri['host'];
        }

        if (array_key_exists('path', $parsedUri)) {
            $normalizedUri['path'] = $parsedUri['path'];
        }

        return HttpUtility::buildUrl($normalizedUri);
    }

    /**
     * @param string $uri
     */
    public static function isValidTrimmedUri($uri): bool
    {
        return filter_var('https://' . $uri, FILTER_VALIDATE_URL) !== false;
    }
}
