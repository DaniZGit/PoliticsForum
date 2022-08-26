<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;

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
}
