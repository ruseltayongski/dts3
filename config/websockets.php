<?php

return [

    /**
     * This package comes with multi tenancy out of the box. Here you can
     * configure the different apps that can use the webSockets server.
     *
     * Optionally you can disable client events so clients cannot send
     * messages through each other via the webSockets.
     */
    'apps' => [
        [
            'id' => env('PUSHER_APP_ID'),
            'name' => env('APP_NAME'),
            'key' => env('PUSHER_APP_KEY'),
            'secret' => env('PUSHER_APP_SECRET'),
            'path' => env('PUSHER_APP_PATH'),
            'capacity' => null,
            'enable_client_messages' => true,
            'enable_statistics' => true,
            'ssl' => true,
            'host' => 'mis.cvchd7.com', // Add your subdomain here
            'port' => 6001, // You can use the default port or change it if needed
            'scheme' => 'https', // Use 'https' since you have an SSL certificate
        ],
    ],

    /**
     * This class is responsible for finding the apps. The default provider
     * will use the apps defined in this config file.
     *
     * You can create a custom provider by implementing the
     * `appProvier` interface.
     */
    'app_provider' => BeyondCode\LaravelWebSockets\Apps\ConfigAppProvider::class,


    /*
     * The maximum request size in kilobytes that is allowed for an incoming WebSocket request.
     */
    'max_request_size_in_kb' => 250,

    /*
     * This path will be used to register the necessary routes for the package.
     */
    'path' => 'laravel-websockets',

    /*
     * Define the optional SSL context for your WebSocket connections.
     * You can see all available options at: http://php.net/manual/en/context.ssl.php
     */
    'ssl' => [
        /*
         * Path to local certificate file on filesystem. It must be a PEM encoded file which
         * contains your certificate and private key. It can optionally contain the
         * certificate chain of issuers. The private key also may be contained
         * in a separate file specified by local_pk.
         */
        'local_cert' => "C:/nginx-1.24.0/nginx-1.24.0/crt/cvchd7.com/STAR_cvchd7_com.pem",
        //'local_cert' => "C:/xampp/apache/crt/site.test/server.crt",
        //'local_cert' => null,

        /*
         * Path to local private key file on filesystem in case of separate files for
         * certificate (local_cert) and private key.
         */
        'local_pk' => "C:/nginx-1.24.0/nginx-1.24.0/crt/cvchd7.com/cvchd7.com.key",
        //'local_pk' => "C:/xampp/apache/crt/site.test/server.key",
        //'local_pk' => null,

        /*
         * Passphrase with which your local_cert file was encoded.
         */
        'passphrase' => null,
        'verify_peer' => false,
        /*
         * Path to your CA bundle file.
         */
        'cafile' => 'C:/nginx-1.24.0/nginx-1.24.0/crt/cvchd7.com/My_CA_Bundle.ca-bundle',
    ],
    'debug' => true,

    'statistics' => [
        /*
         * This model will be used to store the statistics of the WebSocketsServer.
         * The only requirement is that the model should be or extend
         * `WebSocketsStatisticsEntry` provided by this package.
         */
        'model' => \BeyondCode\LaravelWebSockets\Statistics\Models\WebSocketsStatisticsEntry::class,

        /*
         * Here you can specify the interval in seconds at which statistics should be logged.
         */
        'interval_in_seconds' => 60,

        /*
         * When the clean-command is executed, all recorded statistics older than
         * the number of days specified here will be deleted.
         */
        'delete_statistics_older_than_days' => 60
    ],
];