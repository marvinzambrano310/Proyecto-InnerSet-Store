<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Laravel\Scout\Searchable;

class Product extends Model
{
    use Searchable;

    protected $fillable = ['name','stock','price','image','category_id'];
    /**
     * @var mixed
     */


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

    public function toSearchableArray()
    {
        return [
            'name' => $this->name
        ];
    }

}
