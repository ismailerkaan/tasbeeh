<?php

return [
    'firebase' => [
        'service_account_path' => env('FCM_SERVICE_ACCOUNT_PATH'),
        'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
        'send_endpoint_format' => 'https://fcm.googleapis.com/v1/projects/%s/messages:send',
    ],
];
