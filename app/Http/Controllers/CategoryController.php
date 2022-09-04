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
        $postsCount = 0;

        // add posts
        $query->with([
            'posts' => function($q) use($includes){
                    $q->with('user');
                    
                    // add tags to the posts if included
                    $q->when(in_array('tags', $includes), function($q2) {
                        $q2->with('tags');
                    });

                    // add comments to the posts if included
                    $q->when(in_array('comments', $includes), function($q2) use($includes){
                        $q2->with('comments.user');
                        // add replies to the comments if included
                        if (in_array('replies', $includes))
                        {
                            $q2->with(['comments.replies.user']);
                        }
                    });
                    
                    $q->orderBy('updated_at', 'DESC')->limit(25);
                }
        ]);

        $result = $query->get();

        // append posts count of each category to each category
        foreach ($result as $category) {
            $postsCount = Category::where('name', $category['name'])->first()->posts()->count();
            $category['posts_count'] = $postsCount;
        }
        
        return $result;
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
        $tags = $request->query('tags');
        $page = $request->query('page');
        $page = $page === null ? 1 : $page;
        $perPage = $request->query('perPage');
        $perPage = $perPage === null ? 25 : $perPage;
        $postsCount = 0;

        // add posts
        $query->with([
            'posts' => function($q) use($includes, $tags, $page, $perPage, &$postsCount){
                    $q->with('user');
                    // check if filtering by tags
                    if ($tags !== null)
                    {
                        $q->whereHas("tags", function($q2) use ($tags) {
                            $q2->whereIn("name", $tags);
                        }, "=", count($tags));
                    }

                    // get total posts count
                    $postsCount = $q->count();
                    
                    // add tags to the posts if included
                    $q->when(in_array('tags', $includes), function($q2) {
                        $q2->with('tags');
                    });

                    // add comments to the posts if included
                    $q->when(in_array('comments', $includes), function($q2) use($includes){
                        $q2->with('comments.user');
                        // add replies to the comments if included
                        if (in_array('replies', $includes))
                        {
                            $q2->with(['comments.replies.user']);
                        }
                    });
                    
                    
                    $q->orderBy('updated_at', 'DESC')
                        ->offset(($page-1) * $perPage)
                        ->limit($perPage);
                }
        ]);

        $result = $query->where('name', $categoryName)->first();

        // append posts count to the category
        //$postsCount = Category::where('name', $categoryName)->first()->posts()->count();
        $result['posts_count'] = $postsCount;
        return $result;
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
