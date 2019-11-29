<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{

    protected $fillable = ['messages_chat_id'];
    //
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function podcasts()
    {
        return $this->hasMany('App\Podcast')->orderBy('order');
    }
}
