<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Label extends Model
{
    public function posts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_labels');
    }
}
