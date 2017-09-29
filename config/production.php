<?php
/**
 * BE CAREFUL!
 * This section contains the settings of global application
 * @version PRODUCTION
 */

ini_set('display_errors', 'Off');
error_reporting(0);

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
                'username'  =>  '',
                'password'  =>  '',
                'database'  =>  '',
                'driver'    =>  'mysql',
                'charset'   =>  'utf8',
                'debug'     =>  \PDO::ERRMODE_SILENT,
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
        ]
    ],
];