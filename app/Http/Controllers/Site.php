<?php
namespace App\Http\Controllers;

use App\Models;
use App\Services\Image;

class Site extends Controller
{
    /**
     * @return object
     */
    public function index()
    {
        return $this->view('pages.index.index', [
            'posts' => Models\Post::orderBy('created_at', 'DESC')->paginate(10)
        ]);
    }

    /**
     * @param integer $id
     * @param string  $slug
     *
     * @return object
     */
    public function post($id, $slug)
    {
        $post = Models\Post::where('id', $id)->first();

        if (empty($post)) {
            return redirect()->route('site.index');
        }

        if ($post->slug !== $slug) {
            return redirect()->route('site.post', ['id' => $post->id, 'slug' => $post->slug]);
        }

        view()->share(['title' => $post->title]);

        return $this->view('pages.post.post', [
            'post' => $post,
            'interview' => Models\Comment::where('post_id', $post->id)->interview($post),
            'background' => Image\Background::fromString($post->title)
        ]);
    }

    /**
     * @return object
     */
    public function about()
    {
        return $this->view('pages.text.about');
    }
}
