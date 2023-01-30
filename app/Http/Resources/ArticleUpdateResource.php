<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArticleUpdateResource extends JsonResource
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
            'thumbnail' => $this->thumbnail,
            'description' => $this->description,
            'content' => $this->content,
            'video' => $this->video,
            'updated_at' => date_format($this->updated_at, 'd-m-Y H:i:s'), // 'd-m-Y H:i:s
            'author' => $this->whenLoaded('user')->full_name,
            'category' => $this->whenLoaded('category')->name_category,
            'status' => $this->whenLoaded('status')->name_status,
        ];
    }
}
