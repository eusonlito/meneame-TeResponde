<?php
namespace App\Services\Cache;

use App\Services\Filesystem\File;

class Html
{
    /**
    * @var string
    */
    private static $path = 'public/storage/cache';

    /**
     * @var array
     */
    private static $allowedQuery = ['page' => true];

    /**
     * @return boolean
     */
    private static function enabled()
    {
        return env('APP_CACHE') ? true : false;
    }

    /**
     * @param string $key
     *
     * @return boolean
     */
    public static function exists($key = null)
    {
        return self::enabled() && File::exists(self::file($key));
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public static function get($key = null)
    {
        return File::get(self::file($key));
    }

    /**
     * @param string $contents
     * @param string $key
     *
     * @return string
     */
    public static function set($contents, $key = null)
    {
        if (self::enabled() === false) {
            return $contents;
        }

        return File::set(self::file($key), $contents);
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private static function key($key = null)
    {
        if ($key) {
            return str_slug($key);
        }

        $key = app('request')->path();
        $key = ((empty($key) || ($key === '/')) ? 'index' : $key);

        if (($key === 'index') && ($query = getenv('QUERY_STRING'))) {
            parse_str($query, $query);
            $key .= '-'.str_slug(http_build_query(array_intersect_key($query, self::$allowedQuery)));
        }

        return $key;
    }

    /**
     * @param string $key
     *
     * @return string
     */
    private static function file($key)
    {
        return self::$path.'/'.self::key($key).'.html';
    }
}
