<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = ['filename', 'memory'];
    protected $hidden = ['created_at', 'updated_at'];
}
