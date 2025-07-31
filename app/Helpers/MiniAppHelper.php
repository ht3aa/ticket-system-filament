<?php

namespace App\Helpers;

class MiniAppHelper
{
    public static function getProductionMiniAppUrl($miniAppId)
    {
        return config('mini-app.mini_app_production_url') . "?miniapp_id={$miniAppId}&is_web=true";
    }

    public static function getDevelopmentMiniAppUrl($miniAppId)
    {
        return config('mini-app.mini_app_development_url') . "?miniapp_id={$miniAppId}&is_web=true";
    }
}
