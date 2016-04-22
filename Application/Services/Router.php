<?php
namespace Application\Services;

use Application\Exceptions\NotFoundException;
use PHPRouter\Config;
use PHPRouter\Router as Route;
use Application\Exceptions\RouterException;

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
     * @return bool|\PHPRouter\Route
     * @throws NotFoundException
     * @throws RouterException
     */
    public function run() {

        try {
            $routes = Config::loadFromFile($this->routePath);
            $router = Route::parseConfig($routes);

            $exec = $router->matchCurrentRequest();

            if($exec) {
                return $exec;
            }
            throw new NotFoundException('Route does not found', NotFoundException::CODE);
        }
        catch(\Symfony\Component\Yaml\Exception\ParseException $e) {
            throw new RouterException($e->getMessage(), $e->getCode());
        }

    }

}