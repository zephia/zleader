<?php

return [

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
        'FormBuilder' => Kris\LaravelFormBuilder\Facades\FormBuilder::class
    ],
];
