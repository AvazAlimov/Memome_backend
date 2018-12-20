<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $fillable = ["username", "password", "uid"];
    protected $hidden = ["updated_at", "password", "id"];
}
