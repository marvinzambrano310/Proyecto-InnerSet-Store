<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetailRequest extends Model
{
    protected $fillable = ['quantity'];

    public function request()
    {
        return $this->belongsTo('App\Request');
    }

    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
