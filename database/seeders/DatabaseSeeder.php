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
        \App\Models\User::factory(5)->create();
        /*
        // create categories
        $this->createCategories();

        // create tags

        $this->createTags();

        $amount = 60;
        $commentAmount = 100;
        $replyAmount = 75;
        \App\Models\User::factory(100)->create();

        // posts
        \App\Models\Post::factory($amount)->create();

        // tags
        $tags = Tag::all();
        $posts = Post::latest()->limit($amount)->get();
        foreach ($posts as $post) {
            $randNum = rand(2, count($tags));
            $randomTags = Tag::orderBy("name", "ASC")->get()->random($randNum);
            $post->tags()->attach($randomTags);
        }

        // comments
        Comment::factory($commentAmount)->create();

        // replies
        Reply::factory($replyAmount)->create();
        */
    }

    function createCategories() {
        \App\Models\Category::factory(1)->create([
            'name' => 'turizem'
        ]);
        \App\Models\Category::factory(1)->create([
            'name' => 'gospodarstvo'
        ]);
        \App\Models\Category::factory(1)->create([
            'name' => 'kultura'
        ]);
        \App\Models\Category::factory(1)->create([
            'name' => 'kmetijstvo'
        ]);
    }

    function createTags() {
        \App\Models\Tag::factory(1)->create([
            'name' => 'Svoboda'
        ]);

        \App\Models\Tag::factory(1)->create([
            'name' => 'Janša'
        ]);

        \App\Models\Tag::factory(1)->create([
            'name' => 'Golob'
        ]);

        \App\Models\Tag::factory(1)->create([
            'name' => 'Inflacija'
        ]);

        \App\Models\Tag::factory(1)->create([
            'name' => 'Tujina'
        ]);

        \App\Models\Tag::factory(1)->create([
            'name' => 'Predsednik'
        ]);

        \App\Models\Tag::factory(1)->create([
            'name' => 'Vodstvo'
        ]);

        \App\Models\Tag::factory(1)->create([
            'name' => 'Pravica'
        ]);

        \App\Models\Tag::factory(1)->create([
            'name' => 'Narod'
        ]);
        
        \App\Models\Tag::factory(1)->create([
            'name' => 'Protest'
        ]);
    }

}
