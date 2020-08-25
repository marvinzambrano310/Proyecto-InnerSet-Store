<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Request extends JsonResource
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
            'user_id'=>(new UserCollection($this->user_id)),
            'date' => $this->date,
            'detail'=>(new DetailRequestCollection($this->detail)),
            'subtotal' => $this->subtotal,
            'type' => $this->type,
            'surcharge' => $this->surcharge,
            'total'=> $this->total,
            'created_at'=>$this->created_at,
        ];
    }
}
