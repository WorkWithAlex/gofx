<?php

return [
    'mode' => env('RAZORPAY_MODE', 'test'),

    'key' => env('RAZORPAY_MODE') === 'live'
        ? env('RAZORPAY_KEY_LIVE')
        : env('RAZORPAY_KEY_TEST'),

    'secret' => env('RAZORPAY_MODE') === 'live'
        ? env('RAZORPAY_SECRET_LIVE')
        : env('RAZORPAY_SECRET_TEST'),
];
    