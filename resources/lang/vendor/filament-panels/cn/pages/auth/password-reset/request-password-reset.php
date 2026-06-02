<?php

return [

    'title' => '重置您的密码',

    'heading' => '忘记密码？',

    'actions' => [

        'login' => [
            'label' => '返回登录',
        ],

    ],

    'form' => [

        'email' => [
            'label' => '电子邮件地址',
        ],

        'actions' => [

            'request' => [
                'label' => '发送邮件',
            ],

        ],

    ],

    'notifications' => [

        'sent' => [
            'body' => '如果您的账户不存在，您将不会收到该邮件。',
        ],

        'throttled' => [
            'title' => '请求次数过多',
            'body' => '请在 :seconds 秒后重试。',
        ],

    ],

];