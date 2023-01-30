<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
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
            'thumbnail' => $this->thumbnail ? asset('storage/' . $this->thumbnail) : null,
            'description' => $this->description,
            'created_at' => date_format($this->created_at, 'd-m-Y H:i:s'), // 'd-m-Y H:i:s
            'author' => $this->whenLoaded('user')->full_name,
            'category' => $this->whenLoaded('category')->name_category,
        ];
    }
}
