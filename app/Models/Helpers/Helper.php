<?php
namespace App\Models\Helpers;

trait Helper
{
    /**
     * @param string $text
     *
     * @return string
     */
    public static function stripTags($text)
    {
        return strip_tags($text, '<p><br><a><b><strong><i><u><del><em>');
    }
}
