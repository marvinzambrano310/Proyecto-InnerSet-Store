<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailRequest extends Model
{
    protected $fillable = ['quantity','request_id','product_id','final_price'];

    public function request()
    {
        return $this->belongsTo('App\Request');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
