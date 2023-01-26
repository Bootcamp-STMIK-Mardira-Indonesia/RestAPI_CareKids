<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleDetailResource extends JsonResource
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
            'created_at' => date_format($this->created_at, 'd-m-Y H:i:s'), // 'd-m-Y H:i:s
            'author' => $this->whenLoaded('user')->full_name,
            'category' => $this->whenLoaded('category')->name_category,
            'status' => $this->whenLoaded('status')->name_status,
            'comments' => $this->whenLoaded('comments')->pluck('comment'),
        ];
    }
}