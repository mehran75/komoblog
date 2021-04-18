<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Comment extends Model
{

    protected $fillable = [
        'body',
        'author_id',
        'post_id',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }


}
