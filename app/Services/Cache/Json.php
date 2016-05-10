<?php
namespace App\Services\Cache;

use App\Services\Filesystem\File;

class Json
{
    /**
    * @var string
    */
    private static $file = 'public/storage/cache/posts.json';

    /**
     * @return boolean
     */
    public static function exists()
    {
        return File::exists(self::$file);
    }

    /**
     * @return string
     */
    public static function get()
    {
        return File::getJson(self::$file);
    }

    /**
     * @param string $contents
     *
     * @return string
     */
    public static function set($contents)
    {
        return File::set(self::$file, json_encode($contents));
    }
}
