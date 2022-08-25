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
        $categories = Category::all();

        if ($request->query('includes') !== null)
        {
            $includes = $request->query('includes');
            
            if (in_array('posts', $includes))
            {
                foreach ($categories as $category) {
                    $category['posts'] = $category->posts()->with('user')->orderBy('updated_at', 'DESC')->take(25)->get();

                    if (in_array('tags', $includes))
                    {
                        $category['posts']->load('tags');

                        if (in_array('comments', $includes))
                        {
                            if (in_array('replies', $includes))
                            {
                                $category['posts']->load(['comments' => function ($query) {
                                    $query->with('replies');
                                }]);
                            } 
                            else
                            {
                                $category['posts']->load('comments');
                            }
                        }
                    }
                }
            }
        }
        
        return $categories;
    }

    public function getCategoriesPosts () 
    {
        $categories = Category::all();
        foreach ($categories as $category) {
            $posts = $category->posts()->get();
            $category['posts'] = $posts;
            $category['postsCount'] = count($posts);
        }

        return $categories;
    }

    public function getCategoriesPostsDetails () 
    {
        $categories = Category::all();
        foreach ($categories as $category) {
            $posts = $category->posts()->orderBy('updated_at', 'DESC')->take(25)->get();
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

    public function getCategoryPostsDetails ($name) 
    {
        $category = Category::where('name', '=', $name)->first();
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

        return $category;
    }

    public function getCategoryCurrPagePosts ($name, Request $request) {
        $category = Category::where('name', '=', $name)->first();
        $posts = $category->posts()->orderBy('updated_at', 'DESC')->skip((request('page')-1) * request('size'))->take(request('size'))->get();
        $category['posts'] = $posts;
        foreach ($posts as $post) {
            $post['user'] = $post->user()->get();
            $post['tags'] = $post->tags()->get();
            $post['comments'] = $post->comments()->get();
            foreach ($post['comments'] as $comment) {
                $comment['replies'] = $comment->replies()->get();
            }
        }

        return $category;
    }

    public function getCategoryPostsCount($name) {
        $category = Category::where('name', '=', $name)->first();
        
        if(request('tags')) {
            $tags = request('tags');

            // get posts with specific tags
            $posts = $category->posts();
            foreach (explode(',', $tags) as $key => $tag) {
                $posts->whereHas('tags', function ($query) use ($tag) {
                    $query->where('name', $tag);
                });
            }

            $post['count'] = $posts->count();
            return $post;
        } else {
            $post['count'] = $category->posts()->count();
            return $post;
        }

        $post['count'] = $category->posts()->count();
        return json_encode($post);
    }

    public function getCategoriesCount() {
        $categories = Category::all();
        foreach ($categories as $category) {
            $posts = $category->posts()->count();
            $category['postsCount'] = $posts;
        }

        return $categories;
    }

    public function getCategoryFilteredPosts($name){

        if(request('tags')){
            $category = Category::where('name', '=', $name)->first();
            $tags = request('tags');

            // get posts with specific tags
            $posts = $category->posts();
            foreach (explode(',', $tags) as $key => $tag) {
                $posts->whereHas('tags', function ($query) use ($tag) {
                    $query->where('name', $tag);
                });
            }

            $posts = $posts->orderBy('updated_at', 'DESC')->skip((request('page')-1) * request('size'))->take(request('size'))->get();
            //$posts = $posts->limit(request('size'))->orderBy('updated_at', 'DESC')->get();
            $category['posts'] = $posts;

            // add user and tags to the post object
            foreach ($posts  as $post) {
                $post['user'] = $post->user()->get();
                $post['tags'] = $post->tags()->get();
                $post['comments'] = $post->comments()->get();
                foreach ($post['comments'] as $comment) {
                    $comment['replies'] = $comment->replies()->get();
                }
            }

            return $category;
        } else {
            // getCategoryCurrPagePosts funkcija ampk jo nemorm klicat ?
            $category = Category::where('name', '=', $name)->first();
            $posts = $category->posts()->orderBy('updated_at', 'DESC')->skip((request('page')-1) * request('size'))->take(request('size'))->get();
            $category['posts'] = $posts;
            foreach ($posts as $post) {
                $post['user'] = $post->user()->get();
                $post['tags'] = $post->tags()->get();
                $post['comments'] = $post->comments()->get();
                foreach ($post['comments'] as $comment) {
                    $comment['replies'] = $comment->replies()->get();
                }
            }

            return $category;
        }
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

    public function show(Category $category)
    {
        //
    }

    public function edit(Category $category)
    {
        //
    }

    public function update(Request $request, Category $category)
    {
        //
    }

    public function destroy(Category $category)
    {
        //
    }
}
