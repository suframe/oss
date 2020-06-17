<?php

return [
    'driver' => \suframe\oss\driver\QiNiu::class,
    'url' => 'oss/upload',
    'middleware' => function() {
        return config('thinkAdmin.routeMiddleware', []);
    },
    'qiniu' => [
        'accessKey' => '',
        'secretKey' => '',
        'bucketDefault' => 'default', //默认的bucket
        'bucket' => [
            'default' => '' //填写绑定的域名
        ]
    ]
];