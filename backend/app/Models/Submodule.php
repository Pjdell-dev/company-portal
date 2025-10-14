<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submodule extends Model
{
    protected $fillable = ['module_id', 'name', 'code', 'route'];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}