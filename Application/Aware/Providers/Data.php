<?php
namespace Application\Aware\Providers;

/**
 * Class Data
 *
 * @package Application\Aware\Providers
 */
abstract class Data {

    /**
     * Init data service
     *
     * @param array $config
     */
    abstract public function __construct(array $config);

    /**
     * Load prepared data from mapper
     *
     * @return array
     */
    abstract public function load();

    /**
     * Set key for multidimensional array
     *
     * @param array $array
     * @param string $key
     * @return array
     */
    protected function arraySetKey(array $array, $key)
    {
        $result = [];
        if (!empty($array)) {
            foreach ($array as $n => $values) {
                if (isset($values[$key])) {
                    $result[$values[$key]] = $values;
                }
            }
        }

        return $result;
    }
}