<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Reply;
use Nette\Utils\Random;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $amount = 15;
        $commentAmount = 25;
        $replyAmount = 40;
        //\App\Models\User::factory(50)->create();
        // posts
        \App\Models\Post::factory($amount)->create();

        // tags
        $tags = Tag::all();
        $posts = Post::latest()->limit($amount)->get();
        foreach ($posts as $post) {
            $randNum = rand(2, count($tags));
            $randomTags = Tag::orderBy("name", "ASC")->get()->random($randNum);
            $post->tags()->attach($randomTags);
            //$tagIds = array_map(function($tag) { return dd($tag);}, $randomTags);
            /*$tagIds = array();
            foreach ($randomTags as $ind => $tag) {
                array_push($tagIds, $tag->id);
            }
            $post->tags()->attach($tagIds);*/
        }

        // comments
        Comment::factory($commentAmount)->create();

        // replies
        Reply::factory($replyAmount)->create();

    }

}
