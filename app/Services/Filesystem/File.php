<?php
namespace App\Services\Filesystem;

class File
{
    /**
     * @param string $file
     *
     * @return boolean
     */
    public static function exists($file)
    {
        return is_file(self::file($file));
    }

    /**
     * @param string $file
     *
     * @return string
     */
    public static function get($file)
    {
        if (self::exists($file)) {
            return file_get_contents(self::file($file));
        }
    }

    /**
     * @param string $file
     *
     * @return string
     */
    public static function getJson($file)
    {
        return json_decode(self::get($file) ?: []);
    }

    /**
     * @param string $file
     * @param string $contents
     *
     * @return string
     */
    public static function set($file, $contents)
    {
        $file = self::file($file);

        if (!is_dir(dirname($file))) {
            mkdir(dirname($file), 0700, true);
        }

        file_put_contents($file, $contents);

        return $contents;
    }

    /**
     * @param string $file
     *
     * @return string
     */
    public static function file($file)
    {
        return base_path($file);
    }
}
