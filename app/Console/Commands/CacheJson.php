<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Cache;
use App\Models;

class CacheJson extends Command
{
    /**
     * @var string
     */
    protected $signature = 'cache:json';

    /**
     * @var string
     */
    protected $description = 'Create an JSON file with posts';

    /**
     * @return void
     */
    public function handle()
    {
        $this->info('START '.$this->signature.': '.date('Y-m-d H:i:s'));

        $json = [];

        foreach (Models\Post::get() as $post) {
            $json[] = [
                'id' => $post->id,
                'link' => ('post/'.$post->id.'/'.$post->slug),
                'title' => $post->title,
                'user' => $post->user,
                'date' => $post->dateHuman
            ];
        }

        Cache\Json::set($json);

        $this->info('END '.$this->signature.': '.date('Y-m-d H:i:s'));
    }
}
