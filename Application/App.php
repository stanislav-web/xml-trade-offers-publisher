<?php
namespace Application;

/**
 * Class App
 *
 * @package Application
 */
final class App {

    /**
     * Application Logger
     *
     * @var \Application\Services\Logger $logger
     */
    private $logger = null;

    /**
     * Application router
     *
     * @var \Application\Services\Router $router
     */
    private $router = null;

    /**
     * Set app config
     *
     * @param array $config
     */
    public function __construct(array $config) {

        $this->config = $config;

        if(is_null($this->logger) === true) {
            $this->logger = new \Application\Services\Logger($config['logger']);
        }

        if(is_null($this->router) === true) {
            $this->router = new \Application\Services\Router($config['routes']);
        }
    }

    /**
     * Application Logger
     *
     * @return \Application\Services\Logger
     */
    public function getAppLogger() {
        return $this->logger;
    }

    /**
     * Execute application
     *
     * @return bool|\PHPRouter\Route
     * @throws Exceptions\NotFoundException
     */
    public function execute() {
        return $this->router->run();
    }
}