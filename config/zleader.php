<?php

return [
    'notification_sender_email_address' => 'webmaster@microwebsites.com.ar',

    /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */
    'providers' => [
        Cartalyst\DataGrid\Laravel\DataGridServiceProvider::class,
        Kris\LaravelFormBuilder\FormBuilderServiceProvider::class,
        Cviebrock\EloquentSluggable\ServiceProvider::class,
        JanDolata\CrudeCRUD\CrudeCRUDServiceProvider::class,
        SammyK\LaravelFacebookSdk\LaravelFacebookSdkServiceProvider::class,
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
        'DataGrid' => Cartalyst\DataGrid\Laravel\Facades\DataGrid::class,
        'FormBuilder' => Kris\LaravelFormBuilder\Facades\FormBuilder::class,
        'Facebook' => SammyK\LaravelFacebookSdk\FacebookFacade::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Integrations
    |--------------------------------------------------------------------------
    |
    | This array of class integrations will be registered when this application
    | is started.
    |
    */
    'input_integrations' => [
        'FacebookForm' => Zephia\ZLeader\Integrations\Input\FacebookForm::class,
    ],
];
