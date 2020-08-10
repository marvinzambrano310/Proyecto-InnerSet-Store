<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = ['type'];

    public function client()
    {
        return $this->belongsTo('App\User');
    }

    public function detail()
    {
        return $this->hasMany('App\DetailRequest');
    }


}
