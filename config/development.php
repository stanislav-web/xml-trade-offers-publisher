<?php
/**
 * BE CAREFUL!
 * This section contains the settings of global application
 * @version DEVELOPMENT
 */
ini_set('display_errors', 'On');
error_reporting(E_ALL & ~E_WARNING);

return [

    // Route rest configuration
    'routes'    =>  DOCUMENT_ROOT . '../config/routes.yaml',

    // Logger @see http://logging.apache.org/log4php/docs/configuration.html
    'logger'    =>  [
        'rootLogger' => [
            'appenders' => ['error', 'info'],
        ],
        'appenders' => [

            // error log
            'error' => [
                'class' => 'LoggerAppenderFile',
                'layout' => [
                    'class' => 'LoggerLayoutPattern'
                ],
                'params' => [
                    'file' => DOCUMENT_ROOT . '../logs/errors.log',
                    //'conversionPattern' => '%date %logger %-5level %msg%n',
                    'append' => true
                ]
            ],

            // info log
            'info' => [
                'class' => 'LoggerAppenderFile',
                'layout' => [
                    'class' => 'LoggerLayoutPattern'
                ],
                'params' => [
                    'file' => DOCUMENT_ROOT . '../logs/info.log',
                    //'conversionPattern' => '%date %logger %-5level %msg%n',
                    'append' => true
                ]
            ]
        ]
    ],

    // Available services
    'services'  =>  [
        'prom'    =>  [

            // Database configuration
            'db' => [
                'hostname'  =>  'localhost',
                'username'  =>  'dev',
                'password'  =>  '8QSMovWTaS',
                'database'  =>  'CompassCatalogue',
                'driver'    =>  'mysql',
                'charset'   =>  'utf8',
                'debug'     =>  \PDO::ERRMODE_EXCEPTION,
                'connect'   =>  \PDO::ATTR_PERSISTENT,
                'fetching'  =>  \PDO::FETCH_ASSOC,
            ],

            // Output template path
            'templates'  =>  [
                'xml'   =>  DOCUMENT_ROOT . '../Application/Modules/Prom/Views/prom.xml.tpl'
            ],

            // Output header
            'headers'    =>  [
                'xml'   =>  'Content-Type: application/xml; charset=utf-8'
            ],

            // Cache template configuration
            'cache' => [
                'enable'    => true,
                'directory' => DOCUMENT_ROOT . '../cache',
                'ttl'       => 1,
            ]
        ],
        'amazon'    =>  [

            // Create order API
            'orderApi'  => [
                'token'     =>  '', // Compass Api token
                'url'       =>  '', // CSD host
                'siteId'    =>  1,
                'warehouseId'=>  1,
                'currencies' => [
                    'USD'   =>  2,
                    'EUR'   =>  3,
                    'GBP'   =>  4,
                    'CAD'   =>  7
                ],

                // CSD Rest path for order create
                'path'      =>  [
                    'customer'          =>  '/customers',
                    'customerCountries' =>  '/countries',
                    'customerRegions'   =>  '/regions',
                    'customerAddress'   =>  '/addresses',
                    'customerPhones'    =>  '/phones',
                    'customerShipping'  =>  '/shipping-methods',
                    'customerPayment'   =>  '/payment-methods',
                ]
            ],

            //@see http://docs.developer.amazonservices.com/en_US/dev_guide/index.html
            'export'    =>  [
                'auth'      =>  [
                    'awsAccessKeyId'        =>  '',
                    'awsSecretAccessKey'    =>  '',
                    'applicationName'       =>  '',
                    'applicationVersion'    =>  '',
                    'sellerId'              =>  '',
                    'marketplaceId'         =>  '',
                ],
                'statuses'  =>  ['Pending','Shipped'],
                'config'    =>  [
                    'debug'      => false,
                    'ServiceURL' => 'https://mws.amazonservices.ca',
                    'UserAgent'  => 'Red Umbrella',
                    'SignatureVersion' => 2,
                    'SignatureMethod' => 'HmacSHA256',
                    'ProxyHost' => null,
                    'ProxyPort' => -1,
                    'MaxErrorRetry' => 2,
                    'ordersCreatedAfter' => 7889231, // the date time() - ordersCreatedAfter
                ],
            ],
            'templates'  =>  [
                'xml'   =>  DOCUMENT_ROOT . '../Application/Modules/Amazon/Views/amazon.xml.tpl'
            ],
            'headers'    =>  [
                'xml'   =>  'Content-Type: application/xml; charset=utf-8'
            ],
            'cache' => [
                'enable'    => true,
                'directory' => DOCUMENT_ROOT . '../cache',
                'ttl'       => 1,
            ]
        ]
    ],
];