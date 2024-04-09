<?php

namespace App\Helpers;

class AssetHelper {
    public static function asset($path) {
        $manifestPath = public_path('build/manifest.json');

        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);
            if (isset($manifest[$path])) {
                $assetPath = $manifest[$path];
                if (is_array($assetPath)) {
                    $assetPath = reset($assetPath);
                }
                return asset('build/' . $assetPath);
            }
        }

        return asset($path);
    }
}
