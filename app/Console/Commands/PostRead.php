<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models;
use App\Services\Meneame\Api;

class PostRead extends Command
{
    /**
     * @var string
     */
    protected $signature = 'post:read';

    /**
     * @var string
     */
    protected $description = 'Read API to new posts';

    /**
     * @var App\Services\Meneame\Api
     */
    private $api;

    /**
     * @var integer
     */
    private $minimum = 20;

    /**
     * @return void
     */
    public function handle()
    {
        $this->api = new Api;

        $ids = Models\Post::select('remote_id')->lists('remote_id')->toArray();

        foreach ($this->api->getPosts() as $post) {
            if ($this->validPost($post, $ids)) {
                $this->processPost($post);
            }
        }
    }

    /**
     * @param object $post
     * @param array  $ids
     *
     * @return boolean
     */
    private function validPost($post, $ids)
    {
        return !in_array($post->link_id, $ids) && ($post->status === 'published');
    }

    /**
     * @param object $post
     *
     * @return void
     */
    private function processPost($post)
    {
        app('db')->beginTransaction();

        $this->info('Loading Post: "'.$post->title.'"');

        $slug = array_filter(array_map('trim', explode('/', $post->link)));

        $post = Models\Post::create([
            'slug' => end($slug),
            'title' => $post->title,
            'text' => Models\Post::fixText($post->description),
            'link' => $post->link,
            'user' => $post->user,
            'karma' => $post->karma,
            'created_at' => date('Y-m-d H:i:s', strtotime($post->pubDate)),
            'remote_id' => $post->link_id
        ]);

        $insert = [];

        foreach ($this->api->getComments($post->remote_id) as $comment) {
            $insert[] = [
                'text' => Models\Comment::fixText($comment->description),
                'link' => $comment->link,
                'user' => $comment->user,
                'karma' => $comment->karma,
                'number' => $comment->order,
                'created_at' => date('Y-m-d H:i:s', strtotime($comment->pubDate)),
                'remote_id' => $comment->comment_id,
                'post_id' => $post->id
            ];
        }

        $count = count($insert);

        if ($count < $this->minimum) {
            $this->skipPost($count);
        } else {
            $this->savePost($insert, $count);
        }
    }

    /**
     * @param integer $count
     *
     * @return void
     */
    private function skipPost($count)
    {
        app('db')->rollBack();

        $this->error('Skiped post, only '.$count.' comments');
    }

    /**
     * @param array   $insert
     * @param integer $count
     *
     * @return void
     */
    private function savePost($insert, $count)
    {
        Models\Comment::insert($insert);

        $this->info('Inserted '.$count.' comments');

        app('db')->commit();
    }
}
