<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    protected $fillable = ['store_name'];
    public $timestamps = false;

    public function user()
    {
        return $this->morphOne('App\User', 'userable');
    }
}
