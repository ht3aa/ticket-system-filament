<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;

class ImageHelper
{
    /**
     * Get the CloudFront URL for an image stored in S3.
     * If no CloudFront URL is defined, it will return the S3 URL.
     *
     * @param  bool  $temporary  If true, will generate a temporary URL.
     * @param  int  $expirationInMinutes  Expiration time for temporary URLs.
     * @param  array  $options  Options to add width, height, and format.
     */
    public static function getImageUrl(string $imagePath, array $options = []): ?string
    {
        $cloudFrontBaseUrl = 'https://dioe0e9jr96s7.cloudfront.net/';

        // Append query parameters for width, height, and format
        $queryParams = [];
        if (isset($options['width'])) {
            $queryParams['width'] = $options['width'];
        }
        if (isset($options['height'])) {
            $queryParams['height'] = $options['height'];
        }
        if (isset($options['format'])) {
            $queryParams['format'] = $options['format'];
        }
        $queryString = http_build_query($queryParams);

        return $cloudFrontBaseUrl . $imagePath . (! empty($queryString) ? '?' . $queryString : '');
    }

    public static function getPrivateImageUrlFromS3(string $imagePath): ?string
    {
        if (empty($imagePath)) {
            return null;
        }

        return Storage::disk('s3')->temporaryUrl($imagePath, now()->addMinutes(5));
    }
}
