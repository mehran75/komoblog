<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Comment extends Model
{

    public function post(): BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }


}
