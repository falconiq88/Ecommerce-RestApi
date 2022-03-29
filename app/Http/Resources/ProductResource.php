<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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

            'id'=>$this->id,
            'author_id' => $this->user_id,
            'author' => $this->author->name,
            'author_img' => $this->author->picture,
            'category' => $this->category->name,
            'category_id' => $this->category_id,
            'title' => $this->title,
            'product_city'=> $this->product_city,
            'price' => $this->price,
            'body' => $this->body,
            'brand'=>$this->brand,
            'condition'=>$this->condition,
            'images' => $this->images,

        ];
    }
}
