<?php
namespace App\Services\Cache;

class Html
{
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
        return self::enabled() && is_file(self::file($key));
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public static function get($key = null)
    {
        if (self::exists($key)) {
            return file_get_contents(self::file($key));
        }
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

        $file = self::file($key);

        if (!is_dir(dirname($file))) {
            mkdir(dirname($file), 0700, true);
        }

        file_put_contents($file, $contents);

        return $contents;
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
        return base_path('public/storage/cache/'.self::key($key).'.html');
    }
}
