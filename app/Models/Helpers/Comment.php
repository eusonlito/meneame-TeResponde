<?php
namespace App\Models\Helpers;

trait Comment
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
            '$<a class="tooltip [^"]+" href="[^"]+" rel="nofollow">#([0-9]+)</a>$'
        ], [
            '</p>',
            '<a href="#comment-$1">#$1</a>'
        ], strip_tags($text, '<p><a><b><strong><i><u><del><em>'));
    }

    /**
     * @param string $value
     *
     * @return string
     */
    public function getTextHtmlAttribute($value)
    {
        return preg_replace('|<p><a href[^>]+>#[0-9]+</a>\s*|', '<p>', $this->attributes['text']);
    }

    /**
     * @param object $q
     * @param object $post
     *
     * @return object
     */
    public function scopeInterview($q, $post)
    {
        $author = $post->user;
        $rows = $q->orderBy('karma', 'DESC')->get();

        $interview = [];

        foreach ($rows as $row) {
            if (($row->user === $author) || ($row->karma < 10)) {
                continue;
            }

            $response = $this->getInterviewResponse($row->number, $rows, $author);

            if ($response) {
                $interview[] = [
                    'question' => $row,
                    'response' => $response
                ];
            }
        }

        return $interview;
    }

    /**
     * @param integer $number
     * @param object $rows
     * @param string $author
     *
     * @return object
     */
    private function getInterviewResponse($number, $rows, $author)
    {
        foreach ($rows as $row) {
            if (($row->number !== $number) && ($author === $row->user) && strstr($row->text, '>#'.$number.'</a>')) {
                return $row;
            }
        }
    }
}
