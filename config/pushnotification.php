<?php
/**
 * @see https://github.com/Edujugon/PushNotification
 */

return [
    'gcm' => [
        'priority' => 'high',
        'dry_run' => false,
        'apiKey' => '',
    ],
    'fcm' => [
        'priority' => 'high',
        'dry_run' => false,
        'apiKey' => '',
    ],
    'apn' => [
        'certificate' => __DIR__ . '',
        'passPhrase' => '', //Optional
        // 'passFile' => __DIR__ . '/iosCertificates/yourKey.pem', //Optional
        'dry_run' => false,
    ],
];
