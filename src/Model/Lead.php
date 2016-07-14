<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class Lead extends Eloquent {

    protected $table = 'zleader_leads';

    public function values()
    {
        return $this->hasMany('Zephia\ZLeader\Model\LeadValue');
    }
}