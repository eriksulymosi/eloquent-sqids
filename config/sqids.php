<?php

use Sqids\Sqids;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the connections below you wish to use as
    | your default connection for all work. Of course, you may use many
    | connections at once using the manager class.
    |
    */
    'default' => 'main',

    /*
    |--------------------------------------------------------------------------
    | Hashids Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the connections setup for your application. Example
    | configuration has been included, but you may add as many connections as
    | you would like.
    |
    */
    'connections'=> [

        'main' => [
            'alphabet' => Sqids::DEFAULT_ALPHABET,
            'minLength' => Sqids::DEFAULT_MIN_LENGTH,
            'blocklist' => Sqids::DEFAULT_BLOCKLIST,
        ],

        'alternative' => [
            'alphabet' => 'your-alphabets',
            'minLength' => 'your-length-integer',
            'blocklist' => [
                'your',
                'blocklist'
            ],
        ],

    ],

];