<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getCategories(Request $request)
    {
        if ($request->query('includes') === null)
        {
            return Category::all();
        }

        $includes = $request->query('includes');
        
        if (!in_array('posts', $includes)) 
        {
            return Category::all();
        }
        
        $query = Category::query();
        // add posts if included
        $query->with([
            'posts' => function($q) {
                    $q->orderBy('updated_at', 'DESC')->limit(10); 
                },
            'posts.user'
        ]);

        // add tags to the posts if included
        $query->when(in_array('tags', $includes), function($q) {
            $q->with('posts.tags');
        });
        // add comments to the posts if included
        $query->when(in_array('comments', $includes), function($q) use($includes){
            $q->with('posts.comments.user');

            // add replies to the comments if included
            $q->when(in_array('replies', $includes), function($q) {
                $q->with('posts.comments.replies.user');
            });
        });

        return $query->get();
        
    }
    
    public function getCategory($categoryName, Request $request)
    {
        if ($request->query('includes') === null)
        {
            return Category::where('name', $categoryName)->first();
        }

        $includes = $request->query('includes');

        if (!in_array('posts', $includes)) 
        {
            return Category::where('name', $categoryName)->first();
        }
        
        $query = Category::query();
        // add posts
        $query->with([
            'posts' => function($q) {
                    $q->orderBy('updated_at', 'DESC')->limit(10); 
                },
            'posts.user'
        ]);

        // add tags to the posts if included
        $query->when(in_array('tags', $includes), function($q) {
            $q->with('posts.tags');
        });
        // add comments to the posts if included
        $query->when(in_array('comments', $includes), function($q) use($includes){
            $q->with('posts.comments.user');

            // add replies to the comments if included
            $q->when(in_array('replies', $includes), function($q) {
                $q->with('posts.comments.replies.user');
            });
        });

        return $query->where('name', $categoryName)->first();
    }

    public function store(Request $request)
    {
        foreach ($request->categories as $category) {
            Category::create([
                'name' => "$category",
            ]);
        }

        return $request->categories;
    }
}
