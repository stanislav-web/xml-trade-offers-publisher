<?php
/**
 * Startup app definitions
 *
 * @define Document root
 * @define Application path
 * @define Staging development environment
 */

date_default_timezone_set('Europe/Kiev');

defined('DOCUMENT_ROOT') || define('DOCUMENT_ROOT', (isset($_SERVER)) ? $_SERVER["DOCUMENT_ROOT"] : getenv('DOCUMENT_ROOT'));
defined('APP_PATH') || define('APP_PATH', DOCUMENT_ROOT . '/../Application');
define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));
