<?php
namespace App\Services\Cache;

use App\Services\Filesystem\File;

class Asset
{
    /**
    * @var string
    */
    private static $manifest = 'resources/views/assets/manifest.json';

    /**
    * @var array
    */
    private static $assets = [];

    private static function assets()
    {
        return static::$assets ?: (static::$assets = File::getJson(self::$manifest));
    }

    /**
     * @return string
     */
    public static function css()
    {
        $assets = '';

        foreach (self::assets()->css as $asset) {
            $assets .= '<link href="'.asset('css/'.$asset).'" rel="stylesheet">';
        }

        return $assets;
    }

    /**
     * @return string
     */
    public static function js()
    {
        $assets = '';

        foreach (self::assets()->js as $asset) {
            $assets .= '<script src="'.asset('js/'.$asset).'" type="text/javascript"></script>';
        }

        return $assets;
    }
}
