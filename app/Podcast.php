<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Podcast extends Model
{
    //
    use SoftDeletes;

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
