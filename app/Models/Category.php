<?php

namespace App\Models;

use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class Category extends Model
{
    use HasFactory;
    use HasEagerLimit;
    protected $guarded = [];

    // Relationship with Posts
    public function posts() {
        return $this->hasMany(Post::class, 'category_id')->orderBy('updated_at', 'DESC')->limit(10);
    }

    public function scopeFilter($query, array $filters) {
        if($filters['tags'] ?? false){
            $query->whereHas('tags', function ($query) {
                foreach (explode(request('tags'), ',') as $tagName) {
                    $query->where('name', 'like', $tagName);
                }
            });
        }

        /*
        if($filters['search'] ?? false){
            $query->where('title', 'like', '%' . request('search') . '%');
        }
        */
    }
}
