<?php

return [
    'notification_sender_email_address' => env('ZLEADER_DEFAULT_SENDER_EMAIL','website@zephia.com.ar'),

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers/
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */
    'providers' => [
        Kris\LaravelFormBuilder\FormBuilderServiceProvider::class,
        Cviebrock\EloquentSluggable\ServiceProvider::class,
        JanDolata\CrudeCRUD\CrudeCRUDServiceProvider::class,
        SammyK\LaravelFacebookSdk\LaravelFacebookSdkServiceProvider::class,
        Jenssegers\Agent\AgentServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started.
    |
    */
    'aliases' => [
        'FormBuilder' => Kris\LaravelFormBuilder\Facades\FormBuilder::class,
        'Facebook' => SammyK\LaravelFacebookSdk\FacebookFacade::class,
        'Agent' => Jenssegers\Agent\Facades\Agent::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Commands
    |--------------------------------------------------------------------------
    |
    | This array of commands aliases will be registered when this application
    | is started.
    |
    */
    'commands' => [
        Zephia\ZLeader\Console\Commands\ReleaseLeadQueue::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Middlewares
    |--------------------------------------------------------------------------
    |
    | This array of class middlewares will be registered when this application
    | is started.
    |
    */
    'middlewares' => [
        'zleadercors' => Zephia\ZLeader\Http\Middleware\Cors::class,
    ],

];
