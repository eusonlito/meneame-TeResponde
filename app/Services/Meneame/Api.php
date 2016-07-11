<?php
namespace App\Services\Meneame;

class Api
{
    /**
     * @var object
     */
    private $curl;

    /**
     * @var array
     */
    private $categories = ['TeRespondo', 'Pregúntame'];

    /**
     * @return array
     */
    public function getPosts()
    {
        $items = [];

        foreach ($this->categories as $category) {
            $items = array_merge($items, $this->curl()->getXml('/m/'.$category.'/rss'));
        }

        return $items;
    }

    /**
     * @param integer $post_id
     *
     * @return array
     */
    public function getComments($post_id)
    {
        return $this->curl()->getXml('/comments_rss?id='.$post_id);
    }

    /**
     * @return App\Services\Meneame\Request\Curl
     */
    private function curl()
    {
        if ($this->curl) {
            return $this->curl;
        }

        $config = config('api');

        return new Request\Curl($config['url'], [
            'user' => $config['user'],
            'key' => $config['key']
        ]);
    }
}
