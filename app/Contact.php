<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'photo',
        'name',
        'email',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
