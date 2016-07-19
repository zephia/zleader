<?php

namespace Zephia\ZLeader\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Area extends Eloquent {

    protected $table = 'zleader_areas';

    protected $fillable = [
        'name',
        'company_id',
    ];

    public function company()
    {
        return $this->belongsTo('Zephia\ZLeader\Model\Company');
    }
}