<?php
namespace App\Models;

class Post extends Model
{
    use Helpers\Post;

    /**
     * @var string
     */
    protected $table = 'post';

    /**
     * @var string
     */
    public static $foreign = 'post_id';

    /**
     * @return object
     */
    public function comments()
    {
        return $this->hasMany(Comment::class, self::$foreign);
    }
}
