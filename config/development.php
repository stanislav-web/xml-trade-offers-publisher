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
    'routes'    =>  DOCUMENT_ROOT . '/../config/routes.yaml',

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
                    'file' => DOCUMENT_ROOT . '/../logs/errors.log',
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
                    'file' => DOCUMENT_ROOT . '/../logs/info.log',
                    //'conversionPattern' => '%date %logger %-5level %msg%n',
                    'append' => true
                ]
            ]
        ]
    ],

    // Available services
    'services'  =>  [
        'prom'    =>  [

            // Export params
            'params'    =>  [
                'shop'  =>  [
                    'name'          =>  'Pacific Tea',
                    'url'           =>  'http://pacifictea.com',
                    'encoding'      =>  'UTF-8',
                    'currency'      =>  'UAH'
                ],
                'categories'  =>  [
                    28,     // Tea
                    290,    // Herbal Tea
                    309,    // Tea With Flower
                    600     // Tea mix
                ],
                'warehouses'        =>  [1],
                'priceId'           =>  1,
                'photosId'          =>  27,
                'languageId'        =>  1,
                'currency'          =>  1,
                'descriptionId'     =>  299,
                'excludeAttributes' =>  [26, 27, 298, 299]
            ],

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
                'fetching'  =>  \PDO::FETCH_ASSOC
            ],

            // Output template path
            'templates'  =>  [
                'xml'   =>  DOCUMENT_ROOT . '/../Application/Modules/Prom/Views/prom.xml.tpl'
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
        ]
    ],
];