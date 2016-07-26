<?php

namespace Zephia\ZLeader\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Company extends Eloquent {

    protected $table = 'zleader_companies';

    protected $fillable = [
        'name',
        'image',
        'email',
        'phone_number',
        'facebook_url',
        'twitter_url',
        'googleplus_url',
        'terms_url',
        'privacy_url',
        'website_url',
    ];

    public function areas()
    {
        return $this->hasMany('Zephia\ZLeader\Model\Area');
    }
}