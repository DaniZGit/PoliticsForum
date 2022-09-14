<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class PostController extends Controller
{
    public function getPosts(Request $request) {
        if ($request->query('includes') === null)
        {
            return Post::all();
        }

        $includes = $request->query('includes');
        
        $query = Post::query();

        // add category if included
        $query->when(in_array('category', $includes), function($q) {
            $q->with('category');
        });
        // add tags if included
        $query->when(in_array('tags', $includes), function($q) {
            $q->with('tags');
        });
        // add comments if included
        $query->when(in_array('comments', $includes), function($q) use($includes){
            $q->with('comments.user');

            // add replies to the comments if included
            $q->when(in_array('replies', $includes), function($q) {
                $q->with('comments.replies.user');
            });
        });

        return $query->with('user')->get();
    }

    public function getPost($id, Request $request)
    {
        if ($request->query('includes') === null)
        {
            return Post::find($id)->with('user');
        }

        $includes = $request->query('includes');
        
        $query = Post::query();
        
        // add category if included
        $query->when(in_array('category', $includes), function($q) {
            $q->with('category');
        });
        // add tags if included
        $query->when(in_array('tags', $includes), function($q) {
            $q->with('tags');
        });
        // add comments if included
        $query->when(in_array('comments', $includes), function($q) use($includes){
            $q->with('comments.user');

            // add replies to the comments if included
            $q->when(in_array('replies', $includes), function($q) {
                $q->with('comments.replies.user');
            });
        });

        return $query->with('user')->find($id);
    }

    public function createPost(Request $request)
    {   
        $user = $request->user();

        $post = new Post;
        $post->title = $request['title'];
        $post->description = $request['description'];
        $post->user_id = $user['id'];
        $post->category_id = $request['categoryID'];
        $post->save();

        $this->attachTagsToThePost($post, $request['tags']);
        
        return [
            'post' => $post->load(['user', 'tags', 'comments.user', 'comments.replies.user']), 
            'category' => $post->category()->first()
        ];
    }

    function attachTagsToThePost($post, $tags)
    {
        $tagIDs = array();
        foreach ($tags as $tag) {
            $existingTag = Tag::where('name', $tag['name'])->first();
            if(!$existingTag)
            {
                $newTag = new Tag;
                $newTag->name = $tag['name'];
                $newTag->save();
                $tagIDs[] = $newTag['id'];
            }
            else 
            {
                $tagIDs[] = $existingTag['id'];
            }
        }

        $post->tags()->attach($tagIDs);
    }
}
