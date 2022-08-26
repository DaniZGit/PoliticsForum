<?php

namespace App\Models;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reply extends Model
{
    use HasFactory;

    // Relationship To Comment
    public function comment() {
        return $this->belongsTo(Comment::class, 'comment_id');
    }

    // Relationship to User
    public function user() {
        return $this->belongsTo(User::class);
    }
}
