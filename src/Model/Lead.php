<?php

namespace Zephia\ZLeader\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Jenssegers\Agent\Agent;
use Zephia\ZLeader\Model\LeadValue;

class Lead extends Eloquent
{
    protected $table = 'zleader_leads';
    protected $appends = ['remote_platform'];

    public function values()
    {
        return $this->hasMany('Zephia\ZLeader\Model\LeadValue');
    }

    public function getValueByKey($key)
    {
        $values = $this->values->toArray();
        $index = array_search($key, array_column($values, 'key'));

        if ($index !== false) {
            return $values[$index]['value'];
        }
    }

    public function form()
    {
        return $this->belongsTo('Zephia\ZLeader\Model\Form');
    }

    public function getRemotePlatformAttribute($value)
    {
        $agent = new Agent();

        $agent->setUserAgent($value);

        if($agent->isDesktop()) {
            return 'Desktop';
        } elseif($agent->isMobile()) {
            if($agent->isTablet()) {
                return 'Tablet';
            } else {
                return 'Mobile';
            }
        }
    }
}