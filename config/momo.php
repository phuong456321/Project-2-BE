<?php
return [
    'partnerCode' => env('MOMO_PARTNER_CODE'),
    'accessKey' => env('MOMO_ACCESS_KEY'),
    'secretKey' => env('MOMO_SECRET_KEY'),
    'orderInfo' => 'Thanh toán dịch vụ',
    'returnUrl' => 'http://localhost:8000/momo/momo-return',
    'notifyUrl' => 'http://localhost:8000/momo/momo-notify',
];
