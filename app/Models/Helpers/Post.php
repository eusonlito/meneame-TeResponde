<?php
namespace App\Models\Helpers;

trait Post
{
    use Helper;

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
        ], self::stripTags($text));
    }

    /**
     * @return string
     */
    public function getDateHumanAttribute()
    {
        return strftime('%e de %B de %Y', strtotime($this->created_at));
    }
}