<?php
namespace App\Services\Meneame;

class Api
{
    /**
     * @var object
     */
    private $curl;

    /**
     * @return array
     */
    public function getPosts()
    {
        return $this->curl()->getXml('/m/TeRespondo/rss');
    }

    /**
     * @param integer $post_id
     *
     * @return array
     */
    public function getComments($post_id)
    {
        return $this->curl()->getXml('/m/TeRespondo/comments_rss?id='.$post_id);
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
