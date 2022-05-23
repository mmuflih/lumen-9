<?php

/**
 * Created by Muhammad Muflih Kholidin
 * https://github.com/mmuflih
 * muflic.24@gmail.com
 **/

return [
    'cloud' => 'do_space',
    'disks' => [
        'do_space' => [
            'driver' => 's3',
            'key' => env('DO_SPACES_KEY'),
            'secret' => env('DO_SPACES_SECRET'),
            'region' => env('DO_SPACES_REGION'),
            'bucket' => env('DO_SPACES_BUCKET'),
            // 'folder' => env('DO_SPACES_FOLDER'),
            'url' => env('DO_SPACES_URL'),
            'endpoint' => env('DO_SPACES_ENDPOINT'),
            // 'bucket_endpoint' => true,
            'visibility' => 'public',
        ],
    ]
];
