<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;

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
        $user=User::where('id', $this->user_id)->first();
        return [
            'id' => $this->id,
            //'user' => "/api/users/" . $this->user_id,
            'user' => $user,
            'date' => $this->date,
            //'detail'=>"/api/details".$this->detail,
            'detail'=>(new DetailRequestCollection($this->detail)),
            'subtotal' => $this->subtotal,
            'type' => $this->type,
            'surcharge' => $this->surcharge,
            'total'=> $this->total,
            'status' => $this->status,
            'created_at'=>$this->created_at,
        ];
    }
}
