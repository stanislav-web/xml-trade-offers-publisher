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
            'pacifictea'    => [
                // Export params
                'params'    =>  [
                    'shop'  =>  [
                        'name'          =>  'Pacific Tea',
                        'url'           =>  'http://pacifictea.com',
                        'encoding'      =>  'UTF-8',
                        'currency'      =>  'USD',
                        'imgPaths'      =>  'http://img%d.pacifictea.com/f/p600x600/catalogue/%d/%s'
                    ],
                    'categories'  =>  [
                        28,     // Tea
                        290,    // Herbal Tea
                        309,    // Tea With Flower
                        600,    // Tea mix
                    ],
                    'warehouses'        =>  [1],
                    'priceId'           =>  1,
                    'photosId'          =>  27,
                    'languageId'        =>  2,
                    'currency'          =>  1,
                    'brandId'           =>  2,
                    'descriptionId'     =>  299,
                    'countryId'         =>  145,
                    'units'             => [    // attributeId e.g "Weight" represented as 'gr.'
                        3   =>  'gr.'
                    ],
                    'excludeAttributes' =>  [26, 27, 296, 298, 299]
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
                    'xml'   =>  DOCUMENT_ROOT . '/../Application/Modules/Prom/PacificTea/Views/prom.xml.tpl'
                ],

                // Output header
                'headers'    =>  [
                    'xml'   =>  'Content-Type: application/xml; charset=utf-8'
                ],

                // Cache template configuration
                'cache' => [
                    'enable'    => false,
                    'directory' => DOCUMENT_ROOT . '../cache',
                    'ttl'       => 1,
                ]
            ],
            'lecabaret'     => [
                // Export params
                'params'    =>  [
                    'shop'  =>  [
                        'name'          =>  'Lecabaret',
                        'url'           =>  'http://beta.lecabaret.ua',
                        'encoding'      =>  'UTF-8',
                        'currency'      =>  'UAH',
                        'imgPaths'      =>  'http://img%d.beta.lecabaret.ua/f/p600x600/catalogue/%d/%s'
                    ],
                    'categories'  =>  [
                        779,780,781,782,783,784,785,786,787,788,789,790,
                        791,792,793,794,795,796,797,798,799,800,801,802,
                        803,804,805,806,807,808,809,810,811,812,813,814,
                        815,816,817,818,819,820,821,822,823,824,825,826,
                        827,828,829,830,831,832,833,834,835,836,837,838,
                        839,840,841,842,843,844,845,846,847,848,849,850,
                        851,852,853,854,855,856,857,858,859,860,861,862,
                        863,864,865,866,867,868,869,2384,2385,2386,2387,
                        2388,2390,2391,2392,2393,2394,2398,2399,2406,2407,
                        2409,2413,2415,2416,2417,2418,2419,2420,2421,2422,
                        2423,2424,2425,2426,2427,2429,2430,2431,2432,2433,
                        2434,2435,2436,2437,2438,2439,2440,2441,2442,2443,
                        2444,2445,2446,2447,2448,2449,2450,2451,2452,2453,
                        2454,2455,2456,2463,2469,2470,2471,2472,2473,2474,
                        2475,2476
                    ],
                    'warehouses'        =>  [5],
                    'priceId'           =>  13,
                    'photosId'          =>  27,
                    'currency'          =>  10,
                    'brandId'           =>  2,
                    'descriptionId'     =>  299,
                    'countryId'         =>  223,
                    'measurementUnitId' => [    // attributeId e.g "Weight" represented as 'gr.'
                        3   =>  'гр.'
                    ],
                    'excludeAttributes' =>  [26, 27, 296, 298, 299]
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
                    'xml'   =>  DOCUMENT_ROOT . '/../Application/Modules/Prom/PacificTea/Views/prom.xml.tpl'
                ],

                // Output header
                'headers'    =>  [
                    'xml'   =>  'Content-Type: application/xml; charset=utf-8'
                ],

                // Cache template configuration
                'cache' => [
                    'enable'    => false,
                    'directory' => DOCUMENT_ROOT . '../cache',
                    'ttl'       => 1,
                ]
            ],
        ]
    ],
];