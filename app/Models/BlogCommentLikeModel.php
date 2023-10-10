<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCommentLikeModel extends Model
{
    //
    protected $connection = 'mysql';   
	protected $table = 'blog_comment_like';
	protected $guarded = [];
	protected $casts = [
    'created_at' => 'datetime:Y-m-d H:i:s',
    'updated_at' => 'datetime:Y-m-d H:i:s',
];
}
