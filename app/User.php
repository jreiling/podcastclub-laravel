<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //

    public function clubs()
    {
        return $this->belongsToMany('App\Club');
    }

    public function podcasts()
    {
        return $this->hasMany('App\Podcast')->orderBy('order');
    }

    public function nameOrHandle()
    {
      return ($this->first_name == "" ) ? $this->handle : $this->first_name;
    }
}
