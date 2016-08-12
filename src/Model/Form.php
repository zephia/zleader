<?php

namespace Zephia\ZLeader\Model;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Cviebrock\EloquentSluggable\Sluggable;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Form extends Eloquent 
{

    use Sluggable;
    use SluggableScopeHelpers;

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    protected $table = 'zleader_forms';

    protected $fillable = [
        'name',
        'area_id',
        'feedback_url',
        'notification_emails',
        'notification_subject',
        'user_notification_subject',
        'fb_integration_prefix',
        'integration_id',
        'integration_options',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }
}
