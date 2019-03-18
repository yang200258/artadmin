<?php
return [
    'weixin' => [
        'appid' =>'wx44012a9f9aa3ad09',
        'appsecret'=>'8b4219e34cc1a75324ed42cbc8d7629c',
        'home_appid' =>'wxe2f2c66f76dbd611',
        'home_appsecret'=>'183fce5cbef8d09ab2d0d9752fcd6c79',
        'billing_ip' => ""
    ],
    'user.passwordResetTokenExpire' => 3600,
    'image_site' => 'http://artadmintest.fantuan.cn',
    'file_site' => 'http://artadmintest.fantuan.cn',
    'weixin_mini' => [
        'app_id' => 'wx3cf0f39249eb0exx',
        'secret' => 'f1c242f4f28f735d4687abb469072axx',

        // 下面为可选项
        // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
        'response_type' => 'array',

        'log' => [
            'level' => 'debug',
            'file' => __DIR__ . '/wechat.log',
        ],
    ],
    'weixin_mini_template' => [
        'check' => 'xxxxxxxx',
        'pay' => 'xxxxxxxxx',
    ],
];
