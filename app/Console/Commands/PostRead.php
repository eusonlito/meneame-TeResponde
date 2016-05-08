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
        $this->info('START: '.date('Y-m-d H:i:s'));

        $this->api = new Api;

        $post_ids = $this->getPostsIds();

        foreach ($this->api->getPosts() as $post) {
            if ($this->validPost($post)) {
                $this->processPost($post, $post_ids);
            }
        }

        $this->info('END: '.date('Y-m-d H:i:s'));
    }

    /**
     * @return object
     */
    private function getPostsIds()
    {
        return Models\Post::select(['id', 'checksum', 'remote_id'])
            ->get()->keyBy('remote_id');
    }

    /**
     * @param object $post
     *
     * @return object
     */
    private function getCommentsIds($post)
    {
        return Models\Comment::select(['id', 'checksum', 'remote_id'])
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

        $post = $this->getPost($post, $post_ids);

        $insert = [];

        foreach ($this->api->getComments($post->remote_id) as $comment) {
            if ($comment = $this->getComment($comment, $post)) {
                $insert[] = $comment;
            }
        }

        $total = count($insert) + $post->comments->count();

        if ($total < $this->minimum) {
            $this->skipTransaction($total);
        } else {
            $this->commitTransaction($insert);
        }
    }

    /**
     * @param object $post
     * @param object $post_ids
     *
     * @return void
     */
    private function getPost($post, $post_ids)
    {
        $exists = $post_ids->get($post->link_id);

        if (!$exists) {
            $post = $this->insertPost($post);
            $post->comments = collect();

            return $post;
        }

        if ($exists->checksum === $post->checksum) {
            $post = $exists;
        } else {
            $post = $this->updatePost($post, $exists);
        }

        $post->comments = $this->getCommentsIds($post);

        return $post;
    }

    /**
     * @param object $post
     *
     * @return object
     */
    private function insertPost($post)
    {
        $this->info('Insert Post');

        $slug = array_filter(array_map('trim', explode('/', $post->link)));

        return Models\Post::create([
            'slug' => end($slug),
            'title' => $post->title,
            'text' => Models\Post::fixText($post->description),
            'link' => $post->link,
            'user' => $post->user,
            'karma' => $post->karma,
            'checksum' => $post->checksum,
            'created_at' => date('Y-m-d H:i:s', strtotime($post->pubDate)),
            'remote_id' => $post->link_id
        ]);
    }

    /**
     * @param object $post
     * @param object $current
     *
     * @return object
     */
    private function updatePost($post, $current)
    {
        $this->info('Update Post');

        $slug = array_filter(array_map('trim', explode('/', $post->link)));

        $current->update([
            'slug' => end($slug),
            'title' => $post->title,
            'text' => Models\Post::fixText($post->description),
            'link' => $post->link,
            'karma' => $post->karma,
            'checksum' => $post->checksum
        ]);

        return $current;
    }

    /**
     * @param object $comment
     * @param object $post
     *
     * @return array|void
     */
    private function getComment($comment, $post)
    {
        $exists = $post->comments->get($comment->comment_id);

        if (!$exists) {
            return $this->insertComment($comment, $post);
        }

        if ($exists->checksum !== $comment->checksum) {
            $this->updateComment($comment, $exists);
        }
    }

    /**
     * @param object $comment
     * @param object $post
     *
     * @return array
     */
    private function insertComment($comment, $post)
    {
        return [
            'text' => Models\Comment::fixText($comment->description),
            'link' => $comment->link,
            'user' => $comment->user,
            'karma' => $comment->karma,
            'number' => $comment->order,
            'checksum' => $comment->checksum,
            'created_at' => date('Y-m-d H:i:s', strtotime($comment->pubDate)),
            'remote_id' => $comment->comment_id,
            'post_id' => $post->id
        ];
    }

    /**
     * @param object $comment
     * @param object $current
     *
     * @return null
     */
    private function updateComment($comment, $current)
    {
        $current->update([
            'text' => Models\Comment::fixText($comment->description),
            'link' => $comment->link,
            'karma' => $comment->karma,
            'checksum' => $comment->checksum
        ]);

        return $current;
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
        if ($insert) {
            Models\Comment::insert($insert);

            $this->info('Inserted '.count($insert).' comments');
        } else {
            $this->info('No new comments to add');
        }

        app('db')->commit();
    }
}
