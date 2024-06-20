<?php

return [

    'cloudflare' => [
        'api_key' => env('CLOUDFLARE_API_KEY'),
        'account_id' => env('CLOUDFLARE_ACCOUNT_ID')
    ],

    'twilio' => [
        'sid' => env('TWILIO_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        'from' => env('TWILIO_WHATSAPP_FROM'),
        'to' => env('TWILIO_WHATSAPP_TO'),
    ]
];
