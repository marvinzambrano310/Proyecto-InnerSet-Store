<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\User;
use App\Client;

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
        //$data=$this->user_id-1;
        $usertype=Client::where('id', $user->userable_id)->first();

        return [
            'id' => $this->id,
            //'user' => "/api/users/" . $this->user_id,
            'user' => $user,
            'usertype' => $usertype,
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
