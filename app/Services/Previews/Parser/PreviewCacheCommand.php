<?php

namespace App\Services\Previews\Parser;

use Illuminate\Support\Facades\Cache;

class PreviewCacheCommand
{
    public function getCache(string $url)
    {
        if (!$cachedResult = Cache::get($url, false)) {
            return $cachedResult;
        }

        return false;
    }

    public function putCache(string $url, array $tags): bool
    {
        return Cache::put($url, $tags, (60 * 60) * 24);
    }
}
