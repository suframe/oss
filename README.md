# oss
thinkphp6 oss库

### 实现功能：
- 七牛服务器上传文件
- 阿里os（待接入）
- 腾讯oss（待接入）
### 安装
```
//安装七牛sdk库
composer require qiniu/php-sdk
//安装think oss库
composer require suframe/think-oss
```

修改配置文件 config/oss.php
```
<?php

return [
    'driver' => \suframe\oss\driver\QiNiu::class,
    'url' => 'oss/upload', //上传的地址，可自定义
    //上传中间件，目前使用的think-admin后台的中间件做身份过滤，可替换成你自己的中间件完成自己的验证
    'middleware' => function() {
        return config('thinkAdmin.routeMiddleware', []);
    },
    //七牛oss配置
    'qiniu' => [
        'accessKey' => '',
        'secretKey' => '',
        'bucketDefault' => 'default', //默认的bucket
        'bucket' => [
            'default' => '' //填写bucket绑定的域名
        ]
    ]
];
```

### 修改上传
- 1.全局修改
在使用think-admin的时候，可以通过全局修改上传地址上传改造

修改 config/thinkAdmin.php
修改如下：
```
$rs = include (thinkAdminPath() . 'config/thinkAdminDefault.php');
$rs['upload_url'] = url(config('oss.url'))->build();
return $rs;
```

- 2.表单修改 
如果只想部分表单使用oss上传，可在相应的表单项替换：
例如 app/ui/form/NewsForm.php
```
public function image()
{
    return [
        'type' => 'uploadImage',
        'action' => url(config('oss.url'))->build(),
        'callback' => function ($element) {
            $element->data([
                'bucket' => 'suframe',
            ]);
            return $element;
        }
    ];
}
```
修改action即可， 如果对应不同的bucket,在callback 中增加data选项即可

### 非think-admin下使用

非think-admin 下使用，需要自己去增加middleware，做身份过滤。
