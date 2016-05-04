<?php
namespace App\Models;

class Comment extends Model
{
    use Helpers\Comment;

    /**
     * @var string
     */
    protected $table = 'comment';

    /**
     * @var string
     */
    public static $foreign = 'comment_id';

    /**
     * @return object
     */
    public function comments()
    {
        return $this->belongsTo(Post::class, Post::$foreign);
    }
}
