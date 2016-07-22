<?php

namespace Zephia\ZLeader\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Zephia\ZLeader\Model\LeadValue;

class Lead extends Eloquent
{
    protected $table = 'zleader_leads';

    public function values()
    {
        return $this->hasMany('Zephia\ZLeader\Model\LeadValue');
    }

    public function getValueByKey($key)
    {
        $values = $this->values->toArray();
        $index = array_search($key, array_column($values, 'key'));

        return $values[$index]['value'];
    }

    public function form()
    {
        return $this->belongsTo('Zephia\ZLeader\Model\Form');
    }
}