<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "s3", "rackspace"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
//            'url' => env('APP_URL').'storage/app/aduan',
        ],

        'public' => [
            'driver' => 'local',
            'root' => public_path('storage'),
//            'url' => env('APP_URL').'storage',
            'visibility' => 'public',
        ],
        
        'bahan' => [
            'driver' => 'local',
            'root' => public_path('storage/bahan'),
            'url' => 'bahan',
            'visibility' => 'public',
        ],
        
        'bahanpath' => [
            'driver' => 'local',
            'root' => storage_path('app/bahan'),
//            'url' => env('APP_URL').'storage/app',
            'visibility' => 'public',
        ],

        'integritibahan' => [
            'driver' => 'local',
            'root' => public_path('storage/integriti'),
            'url' => 'integriti',
            'visibility' => 'public',
        ],
        
        'integritibahanpath' => [
            'driver' => 'local',
            'root' => storage_path('app/integriti'),
//            'url' => env('APP_URL').'storage/app',
            'visibility' => 'public',
        ],
        
        'tips' => [
            'driver' => 'local',
            'root' => public_path('storage/tips'),
            'url' => 'storage/tips',
            'visibility' => 'public',
        ],
        
        'letter' => [
            'driver' => 'local',
            'root' => public_path('storage/letter'),
            'url' => '/storage/letter',
            'visibility' => 'public',
        ],
        
        'portal' => [
            'driver' => 'local',
            'root' => public_path('storage/portal'),
            'url' => '/storage/portal',
            'visibility' => 'public',
        ],

        'profile' => [
            'driver' => 'local',
            'root' => public_path('storage/profile'),
            'url' => '/storage/profile',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_KEY'),
            'secret' => env('AWS_SECRET'),
            'region' => env('AWS_REGION'),
            'bucket' => env('AWS_BUCKET'),
        ],
        
        'backup' => [
            'driver' => 'local',
            'root' => public_path('storage/backups'),
        ],

        'exports' => [
            'driver' => 'local',
            'root' => public_path('storage/exports'),
        ],

        // ftp setting detail for integration with ECC server.
        'ftp' => [
            'driver' => 'ftp',
            'host' => env('FTP_HOST'),
            'username' => env('FTP_USERNAME'),
            'password' => env('FTP_PASSWORD'),
        ],
    ],

];
