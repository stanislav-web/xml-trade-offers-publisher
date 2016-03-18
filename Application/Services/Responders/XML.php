<?php
namespace Application\Services\Responders;
use Application\Aware\Providers\Export;
use Application\Exceptions\BadResponseException;

/**
 * Class XML
 *
 * @package Application\Services\Responders
 */
class XML extends Export {

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
     * @return array|object
     */
    public function getData() {

        try {

            if($this->response->isSuccessful() == true) {

                return $this->response->{$this->type}();
            }
            throw new BadResponseException('Load data unsuccessfull');
        }
        catch(BadResponseException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

    }
}