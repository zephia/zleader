<?php

namespace Zephia\ZLeader\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class LeadValue extends Eloquent {

    protected $table = 'zleader_leads_values';
    protected $hidden = ['id', 'created_at', 'updated_at', 'lead_id', 'field'];
    protected $appends = ['label'];

    public function lead()
    {
        return $this->belongsTo('Zephia\ZLeader\Model\Lead');
    }

    public function field()
    {
        return $this->belongsTo('Zephia\ZLeader\Model\Field', 'key', 'key');
    }

     public function getLabelAttribute()
    {
        return $this->field->name;
    }
}