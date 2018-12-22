<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed pictures
 */
class Memory extends Model
{
    protected $fillable = ['account', 'title', 'content', 'date'];

    public function photoPaths()
    {
        $paths = $this->hasMany("App\Photo", "memory", "id")
            ->get()
            ->pluck("filename");

        foreach ($paths as $index => $path) {
            $paths[$index] = url('/') . "/storage/" . $path;
        }

        return $paths;
    }

    public function normalize()
    {
        $this->pictures = $this->photoPaths();
    }
}
