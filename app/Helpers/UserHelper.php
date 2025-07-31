<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;

class UserHelper
{
    /**
     * Get the user associated with the current x-mini-app-id.
     */
    public static function getUserFromMiniAppId(): ?User
    {
        // Get the x-mini-app-id from the request header
        $miniAppId = Request::header('x-mini-app-id');

        $miniAppId = self::extractNumberBeforeQueryString($miniAppId);
        if (! $miniAppId) {
            return null; // Return null if x-mini-app-id is missing
        }

        // Cache the result to avoid repeated database queries
        return Cache::remember("user_for_mini_app_id_{$miniAppId}", 60, function () use ($miniAppId) {
            return User::find($miniAppId); // Find the user by x-mini-app-id
        });
    }

    /**
     * Get the user ID associated with the current x-mini-app-id.
     */
    public static function getUserIdFromMiniAppId(): ?int
    {
        $user = self::getUserFromMiniAppId();

        return $user?->id; // Return user ID or null if no user found
    }

    public static function extractNumberBeforeQueryString(?string $url): ?int
    {
        if ($url && preg_match('/(\d+)(\?|$)/', $url, $matches)) {
            return (int) $matches[1]; // Return the number as an integer
        }

        return null; // Return null if no match is found or if URL is null
    }
}
