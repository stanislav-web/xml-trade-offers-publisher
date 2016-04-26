<?php
namespace Application\Aware\Providers;

/**
 * Class Model
 *
 * @package Application\Aware\Providers
 */
abstract class Model {

    /**
     * Reverse object to real array for all public properties
     *
     * @return array
     */
    abstract public function toArray();
}