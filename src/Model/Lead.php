<?php

namespace Zephia\ZLeader\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Jenssegers\Agent\Agent;
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

        if ($index !== false) {
            return $values[$index]['value'];
        }
    }

    public function form()
    {
        return $this->belongsTo('Zephia\ZLeader\Model\Form');
    }

    public function setUserAgentAttribute($value)
    {
        $agent = new Agent();

        $agent->setUserAgent($value);

        if($agent->isDesktop()) {
            $platform = 'Desktop';
        } elseif($agent->isMobile()) {
            if($agent->isTablet()) {
                $platform = 'Tablet';
            } else {
                $platform = 'Mobile';
            }
        }

        $this->attributes['remote_platform'] = $platform;

        $this->attributes['user_agent'] = $value;
    }
}