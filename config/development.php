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
    'router'    =>  DOCUMENT_ROOT . '../config/routes.yaml'
];