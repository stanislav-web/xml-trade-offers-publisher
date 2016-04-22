<?php
namespace Application\Modules\Prom\Services;

use Application\Services\Database;

/**
 * Class ProductService
 *
 * @package Application\Modules\Prom\Service
 */
class ProductService {

    /**
     * Service config
     *
     * array $config
     */
    private $config = [];

    /**
     * Database service
     *
     * \Application\Services\Database $db
     */
    private $db = null;

    /**
     * Init connection
     */
    public function __construct(array $config) {

        $this->config = $config;

        if(is_null($this->db) === true) {
            $this->db = new Database($this->config['db']);
        }
    }
}