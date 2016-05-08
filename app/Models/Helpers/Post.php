<?php
namespace App\Models\Helpers;

trait Post
{
    /**
     * @param string $text
     *
     * @return string
     */
    public static function fixText($text)
    {
        return preg_replace([
            '$</p>.*$',
            '$<img[^>]+>$',
        ], [
            '</p>',
            '',
        ], $text);
    }
}