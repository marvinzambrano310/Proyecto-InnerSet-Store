<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'stock' => $this->stock,
            'price' => $this->price,
            'image' => $this->image,
            'user' => "api/user".$this->user_id,
            'category' => "api/categories".$this->category_id,
        ];
    }
}
