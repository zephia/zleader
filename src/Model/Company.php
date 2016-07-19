<?php

namespace Zephia\ZLeader\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Company extends Eloquent {

    protected $table = 'zleader_companies';

    protected $fillable = [
        'name',
        'image',
    ];

    public function areas()
    {
        return $this->hasMany('Zephia\ZLeader\Model\Area');
    }
}