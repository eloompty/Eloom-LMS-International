<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Firebase Web App
    |--------------------------------------------------------------------------
    |
    | Client-side configuration for Firebase Cloud Messaging browser push.
    | These values are public by design: they are served to the browser and
    | identify the Firebase project rather than authenticating against it.
    | Access is controlled by the authorised domains of the Firebase project.
    |
    | Leave FIREBASE_PROJECT_ID empty to disable browser push notifications;
    | the messaging scripts are then omitted from the portal layouts.
    |
    */

    'web' => [
        'apiKey' => env('FIREBASE_API_KEY'),
        'authDomain' => env('FIREBASE_AUTH_DOMAIN'),
        'projectId' => env('FIREBASE_PROJECT_ID'),
        'storageBucket' => env('FIREBASE_STORAGE_BUCKET'),
        'messagingSenderId' => env('FIREBASE_MESSAGING_SENDER_ID'),
        'appId' => env('FIREBASE_APP_ID'),
        'measurementId' => env('FIREBASE_MEASUREMENT_ID'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Web Push Certificate (VAPID)
    |--------------------------------------------------------------------------
    |
    | The public key pair from Firebase Console > Project Settings > Cloud
    | Messaging > Web configuration. Also public, and required to mint browser
    | messaging tokens.
    |
    */

    'vapid_key' => env('FIREBASE_VAPID_KEY'),

];
