<?php
namespace Application\Services;

use Logger as Log;

/**
 * Class Logger
 *
 * @package Application\Services
 */
class Logger {

    /**
     * @var Log $logger
     */
    private $logger;

    /**
     * Init logger
     *
     * @param array $config
     */
    public function __construct(array $config) {
        Log::configure($config);
        $this->logger = Log::getRootLogger();
    }

    /**
     * Get logger adapter
     *
     * @return Log
     */
    public function getLogger() {
        return $this->logger;
    }


    /**
     * Print message as json
     *
     * @return string
     */
    public function json($message) {
        echo json_encode($message);
        return $this;
    }
}