<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class System extends Model
{
    protected $fillable = ['name', 'code'];

    public function modules()
    {
        return $this->hasMany(Module::class);
    }
}