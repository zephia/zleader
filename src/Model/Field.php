<?php

namespace Zephia\ZLeader\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Field extends Eloquent
{
    protected $table = 'zleader_fields';

    protected $fillable = ['name', 'key', 'filtrable', 'columnable'];

    public function scopeColumnables($query)
    {
        return $query->where('columnable', '=', true);
    }

    public function scopeFiltrables($query)
    {
        return $query->where('filtrable', '=', true);
    }
}