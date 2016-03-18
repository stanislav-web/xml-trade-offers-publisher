<?php

// Require definitions
require_once '../config/definitions.php';

// Require composite libraries
require_once DOCUMENT_ROOT . '../vendor/autoload.php';

// Require global configurations
require_once DOCUMENT_ROOT . '../config/'.APPLICATION_ENV.'.php';

// init application
$app = new Application\App($config);

try {

    // start application
    return $app->execute();

} catch (\Exception $e) {

    $message = ['error' => [
        'code' => $e->getCode(),
        'message' => $e->getMessage()
    ]];

    // log message
    $errorLog = $app->getAppLogger();
    $errorLog->json($message)->getLogger()->error($e->getMessage());
    //(APPLICATION_ENV != 'production') ? $errorLog->json($message) : $errorLog->getLogger()->error($e->getMessage());

}