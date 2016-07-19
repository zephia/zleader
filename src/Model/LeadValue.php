<?php

namespace Zephia\ZLeader\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class LeadValue extends Eloquent {

    protected $table = 'zleader_leads_values';

    public function lead()
    {
        return $this->belongsTo('Zephia\ZLeader\Model\Lead');
    }
}