<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Product extends Model
{
    protected $fillable = ['name','stock','price','image','category_id'];

    public static function boot(){
        parent::boot();

        static::creating(function ($product) {
            $product->user_id = Auth::id();
        });
    }
    public function owner()
    {
        return $this->belongsTo('App\User');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function detail()
    {
        return $this->hasMany('App\DetailRequest');
    }
}
