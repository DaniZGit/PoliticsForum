<?php

namespace App\Models;

use App\Models\Tag;
use App\Models\User;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use \Staudenmeir\EloquentEagerLimit\HasEagerLimit;

class Post extends Model
{
    use HasFactory;
    use HasEagerLimit;
    protected $guarded = [];

    public function tags(){
		return $this->belongsToMany(Tag::class);
	}

    // Relationship To User
    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship To Category
    public function category() {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Relationship To Comments
    public function comments() {
        return $this->hasMany(Comment::class);
    }
}
