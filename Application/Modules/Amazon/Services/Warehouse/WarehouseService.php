<?php
namespace Application\Modules\Amazon\Services\Warehouse;

use Application\Exceptions\NotFoundException;

/**
 * Class WarehouseService
 *
 * @package Application\Modules\Amazon\Services\Warehouse
 */
class WarehouseService {

    /**
     * API Config
     *
     * @var array Api
     */
    private $apiConfig = null;

    /**
     * Init
     *
     * @param array $apiConfig api config
     */
    public function __construct(array $apiConfig) {

        $this->apiConfig = $apiConfig;
    }

    /**
     * Get warehouse Id
     *
     * @throws NotFoundException
     * @return int
     */
    public function getWarehouseId() {

        if(isset($this->apiConfig['warehouseId']) === false) {
            throw new NotFoundException('warehouseId param does not found in configuration file', NotFoundException::CODE);
        }

        return (int)$this->apiConfig['warehouseId'];
    }

}