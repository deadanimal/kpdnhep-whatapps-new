<?php

/**
 * Feedback module's config file
 */
return [
    'waboxapp' => [
        'token' => env('WABOXAPP_TOKEN', ''),
        'uid' => env('WABOXAPP_UID', ''),
        'alert_1' => env('WABOXALERT_1', 'syafiq@myopensoft.net'),
        'alert_2' => env('WABOXALERT_2', 'sitizakiah@kpdnhep.gov.my'),
        'alert_3' => env('WABOXALERT_3', 'akmal.ismail@kpdnhep.gov.my'),
        'alert_4' => env('WABOXALERT_4', 'tmukmkd@gmail.com'),
    ],
    'twitter' => [
        'token' => env('TWITTER_TOKEN', ''),
        'uid' => env('TWITTER_UID', ''),
    ],
    'status' => [
        'bot_token' => env('FEEDBACK_STATUS_BOT_TOKEN', ''),
        'chat_id' => env('FEEDBACK_STATUS_CHAT_ID', ''),
    ],
    'telegram_bot' => [
        'chat_id' => env('TELEGRAM_CHAT_ID'),
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
    ],
    'telegram_bot_chat' => [
        'bot_id' => env('TELEGRAM_BOT_ID'),
        'token' => env('TELEGRAM_BOT_CHAT_TOKEN'),
    ]
];