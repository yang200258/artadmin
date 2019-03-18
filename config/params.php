<?php
$miniAppId = 'wx44012a9f9aa3ad09';
$miniSecret = '8b4219e34cc1a75324ed42cbc8d7629c';

return [
    'weixin' => [
        'appid' => $miniAppId,
        'appsecret'=> $miniSecret,
        'home_appid' =>'wxe2f2c66f76dbd611',
        'home_appsecret'=>'183fce5cbef8d09ab2d0d9752fcd6c79',
        'billing_ip' => ""
    ],
    'user.passwordResetTokenExpire' => 3600,
    'image_site' => 'https://static.hnyskj.net',
    'file_site' => 'https://www.hnyskj.net',

    // easywechat小程序初始化
    'weixin_mini' => [
        'app_id' => $miniAppId,
        'secret' => $miniSecret,

        // 下面为可选项
        // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
        'response_type' => 'array',

        'log' => [
            'level' => 'debug',
            'file' => 'runtime/wechat.log',
        ],
    ],
    'weixin_mini_template' => [
        'check' => 'NhesljnePGi3YJxNJW_57VPxD4rlU2_3iUq0EWKchos',
        'pay' => 'mmJyYwZ5j5Yi97ckh0dVc3-4hgiysv8CVRX3xn9-fSw',
    ],
];
