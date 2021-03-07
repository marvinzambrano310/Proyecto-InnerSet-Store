<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;


class Request extends Model
{
    protected $fillable = ['date','subtotal','type','surcharge','total', 'status'];

    public static function boot(){
        parent::boot();

        static::creating(function ($request) {
            $request->user_id = Auth::id();
        });
    }

    public function client()
    {
        return $this->belongsTo('App\User');
    }

    public function detail()
    {
        return $this->hasMany('App\DetailRequest');
    }

}
