<?php
namespace Application\Aware\Factories;
use Application\Exceptions\InternalServerErrorException;
use Application\Exceptions\ExportFactoryException;

/**
 * Class ExportFactory
 *
 * @package Application\Aware\Factories
 */
class ExportFactory {

    /**
     * Responders namespace
     *
     * @const RESPONDERS_NAMESPACE
     */
    const RESPONDERS_NAMESPACE = '\Application\Services\Responders\\';

    /**
     * Load class name
     *
     * @var string $class
     */
    private $class;


    /**
     * Class params
     *
     * @var array $params
     */
    private $params = [];

    /**
     * Check instance
     *
     * @param array $params
     * @throws ExportFactoryException
     */
    public function __construct(array $params = []) {

        $this->params = $params;
        $this->class = self::RESPONDERS_NAMESPACE.$this->params['type'];

        if(class_exists($this->class) === false) {
            throw new ExportFactoryException("Responder error: class ".$this->class." does not exist", InternalServerErrorException::CODE);
        }
    }

    /**
     * Simple create object from instance
     *
     * @throws \Exception
     * @return \Application\Aware\Providers\Export
     */
    public function load() {

        try {
            return new $this->class($this->params['url'], $this->params);
        }
        catch(InternalServerErrorException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
        catch(\InvalidArgumentException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }
}