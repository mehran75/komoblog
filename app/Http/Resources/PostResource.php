<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'excerpt' => $this->excerpt,
            'author_id' => $this->author_id,
            'is_published' => $this->is_published,
            'photo' => asset('images/'.$this->photo),
            'created_at' => $this->created_at,
            'categories' => $this->categories,
            'labels' =>  $this->labels

        ];
    }
}
