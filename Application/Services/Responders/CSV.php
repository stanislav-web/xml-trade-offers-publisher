<?php
namespace Application\Services\Responders;

use Application\Aware\Providers\Export;

/**
 * Class CSV
 *
 * @package Application\Services\Responders
 */
class CSV extends Export {

    /**
     * Load source with request options
     *
     * @param string $source
     * @param array $options
     */
    public function __construct($source = '', array $options = []) {
        parent::__construct($source, $options);
    }

    /**
     * Get response data
     *
     * @return array
     */
    public function getData() {

    }
}