<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailRequest extends JsonResource
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
            'product' => new Product($this->product),
            'quantity' => $this->quantity,
            'finalprice' => $this->final_price,
            'created_at' => $this->created_at,

        ];
    }
}
