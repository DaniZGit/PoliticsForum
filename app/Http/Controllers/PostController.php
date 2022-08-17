<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function getPosts() {
        $categories = Category::all();
        foreach ($categories as $category) {
            $posts = $category->posts()->orderBy('updated_at', 'DESC')->get();
            $category['posts'] = $posts;
            foreach ($posts as $post) {
                $post['user'] = $post->user()->get();
                $post['tags'] = $post->tags()->get();
                $post['comments'] = $post->comments()->get();
                foreach ($post['comments'] as $comment) {
                    $comment['replies'] = $comment->replies()->get();
                }
            }
        }

        return $categories;
    }

    public function getPost($id)
    {
        $post = Post::find($id);
        $post['user'] = $post->user()->get();
        $post['tags'] = $post->tags()->get();
        $post['comments'] = $post->comments()->get();
        foreach ($post['comments'] as $comment) {
            $comment['replies'] = $comment->replies()->get();
        }
        return $post;
    }
}
