<?php
namespace App\Services\Meneame\Cache;

class Cache
{
    /**
     * @var integer
     */
    private static $expires = 3600;

    /**
     * @param mixed
     *
     * @return string
     */
    public static function key()
    {
        return md5(var_export(func_get_args(), true));
    }

    /**
     * @param string $key
     *
     * @return boolean
     */
    public static function exists($key)
    {
        $file = self::file($key);

        return is_file($file) && ((filemtime($file) + self::$expires) > time());
    }

    /**
     * @param string $key
     *
     * @return string
     */
    public static function get($key)
    {
        if (self::exists($key)) {
            return file_get_contents(self::file($key));
        }
    }

    /**
     * @param string $key
     * @param string $contents
     *
     * @return string
     */
    public static function set($key, $contents)
    {
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
    private static function file($key)
    {
        return storage_path('cache/curl/'.substr($key, 0, 3).'/'.substr($key, 3));
    }
}
