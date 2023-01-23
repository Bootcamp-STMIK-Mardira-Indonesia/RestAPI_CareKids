<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'image' => $this->image,
            'description' => $this->description,
            'content' => $this->content,
            'video' => $this->video,
            'author' => $this->user->username, // 'user_id'
            'category' => $this->category->name_category, // 'category_id'
            'status' => $this->status,
            'created_at' => date_format($this->created_at, 'd-m-Y H:i:s'), // 'd-m-Y H:i:s
            'updated_at' => date_format($this->updated_at, 'd-m-Y H:i:s'),
        ];
    }
}
