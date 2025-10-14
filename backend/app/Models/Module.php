<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = ['system_id', 'name', 'code', 'icon'];

    public function system()
    {
        return $this->belongsTo(System::class);
    }

    public function submodules()
    {
        return $this->hasMany(Submodule::class);
    }
}