<?php
namespace Application\Aware\Providers;

/**
 * Class Provider
 *
 * @package Application\Aware\Providers
 */
abstract class Client {

    /**
     * Client debug
     *
     * @var boolean $debug
     */
    protected $debug = false;

    /**
     * Init parent loader
     *
     * @param        $client
     * @param array  $merchant
     * @param string $debugProperty
     * @throws \Exception
     */
    public function __construct($client, array $merchant, $debugProperty = '') {
        $this->debug($client, $debugProperty);
    }

    /**
     * List all orders updated after a certain date
     *
     * @param string $orderDate
     * @param array $orderStatuses
     * @return void
     */
    abstract function getOrders($orderDate, array $orderStatuses);

    /**
     * List of all order's items
     *
     * @param array $orderIds
     * @return mixed
     */
    abstract function loadOrderItems(array $orderIds);

    /**
     * Init debug
     *
     * @param object      $object
     * @param string $property
     * @throws \Exception
     */
    protected function debug($object, $property = '') {

        $class = get_class($object);
        $reflectionClass = new \ReflectionClass($class);

        $reflectionProperty = $reflectionClass->getProperty($property);
        $reflectionProperty->setAccessible(true);

        if(isset($reflectionProperty->getValue($object)['debug']) === false) {
            throw new \Exception('Debug flag is not defined in configuration file');
        }

        $this->debug = $reflectionProperty->getValue($object)['debug'];
    }
}