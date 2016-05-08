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
        ], [
            '</p>',
        ], strip_tags($text, '<p><a><b><strong><i><u><del><em>'));
    }
}