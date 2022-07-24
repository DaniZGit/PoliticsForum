<?php

namespace App\Models;

use App\Models\Post;
use App\Models\Reply;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    // Relationship To Post
    public function post() {
        return $this->belongsTo(Post::class, 'post_id');
    }

    // Relationship To Replies
    public function replies() {
        return $this->hasMany(Reply::class);
    }
}
