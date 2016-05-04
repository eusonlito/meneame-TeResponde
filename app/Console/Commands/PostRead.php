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

        $post_ids = $this->getPostsIds();

        foreach ($this->api->getPosts() as $post) {
            if ($this->validPost($post)) {
                $this->processPost($post, $post_ids);
            }
        }
    }

    /**
     * @return object
     */
    private function getPostsIds()
    {
        return Models\Post::select(['id', 'remote_id'])->get()->keyBy('remote_id');
    }

    /**
     * @param object $post
     *
     * @return object
     */
    private function getCommentsIds($post)
    {
        return Models\Comment::select(['id', 'remote_id'])
            ->where('post_id', $post->id)
            ->get()->keyBy('remote_id');
    }

    /**
     * @param object $post
     *
     * @return boolean
     */
    private function validPost($post)
    {
        return ($post->status === 'published');
    }

    /**
     * @param object $post
     * @param object $post_ids
     *
     * @return void
     */
    private function processPost($post, $post_ids)
    {
        app('db')->beginTransaction();

        $this->info('Loading Post: "'.$post->title.'"');

        if ($exists = $post_ids->get($post->link_id)) {
            $this->info('Post already exists');

            $post = $exists;
            $comment_ids = $this->getCommentsIds($post);
        } else {
            $this->info('Insert new post');

            $post = $this->insertPost($post);
            $comment_ids = collect();
        }

        $insert = [];

        foreach ($this->api->getComments($post->remote_id) as $comment) {
            if ($comment_ids->get($comment->comment_id)) {
                continue;
            }

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

        $total = count($insert) + $comment_ids->count();

        if ($total < $this->minimum) {
            $this->skipTransaction($total);
        } elseif ($insert) {
            $this->commitTransaction($insert);
        } else {
            $this->info('No new comments to add');
        }
    }

    /**
     * @param object $post
     *
     * @return object
     */
    private function insertPost($post)
    {
        $slug = array_filter(array_map('trim', explode('/', $post->link)));

        return Models\Post::create([
            'slug' => end($slug),
            'title' => $post->title,
            'text' => Models\Post::fixText($post->description),
            'link' => $post->link,
            'user' => $post->user,
            'karma' => $post->karma,
            'created_at' => date('Y-m-d H:i:s', strtotime($post->pubDate)),
            'remote_id' => $post->link_id
        ]);
    }

    /**
     * @param integer $total
     *
     * @return void
     */
    private function skipTransaction($total)
    {
        app('db')->rollBack();

        $this->error('Skiped post, only '.$total.' comments');
    }

    /**
     * @param array   $insert
     *
     * @return void
     */
    private function commitTransaction($insert)
    {
        Models\Comment::insert($insert);

        $this->info('Inserted '.count($insert).' comments');

        app('db')->commit();
    }
}
