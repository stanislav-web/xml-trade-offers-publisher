<?php
namespace Application\Services;

use Application\Exceptions\NotFoundException;
use PHPRouter\Config;
use PHPRouter\Router as Route;

/**
 * Class Router
 *
 * @package Application\Services
 */
class Router {

    /**
     * Routes configuration
     *
     * @var string $routePath
     */
    private $routePath;

    /**
     * Router exist checkout
     *
     * @param string $routePath
     * @throws NotFoundException
     */
    public function __construct($routePath) {

        if(file_exists($routePath) != true) {
            throw new NotFoundException($routePath.' file does not found', NotFoundException::CODE);
        }
        $this->routePath = $routePath;
    }

    /**
     * Router runner
     *
     * @throws NotFoundException
     * @return bool|\PHPRouter\Route
     */
    public function run() {

        $routes = Config::loadFromFile($this->routePath);
        $router = Route::parseConfig($routes);

        $exec = $router->matchCurrentRequest();


        if($exec) {
            return $exec;
        }
        throw new NotFoundException('Route does not found', NotFoundException::CODE);
    }

}