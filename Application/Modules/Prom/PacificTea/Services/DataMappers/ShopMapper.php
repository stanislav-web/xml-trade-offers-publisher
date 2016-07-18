<?php
namespace Application\Modules\Prom\PacificTea\Services\DataMappers;

use Application\Aware\Providers\Data;
use Application\Modules\Prom\PacificTea\Models\ShopModel;
use Application\Exceptions\InternalServerErrorException;

/**
 * Class ShopMapper
 *
 * @package Application\Modules\Prom\Services\DataMappers
 */
class ShopMapper extends Data {

    /**
     * Service config
     *
     * array $config
     */
    private $config = [];

    /**
     * Init data service
     *
     * @param array $config
     * @throws InternalServerErrorException
     */
    public function __construct(array $config) {

        if(!isset($config['params']['shop'])) {
            throw new InternalServerErrorException('Config shop undefined. See config production.php (development.php)');
        }
        $this->config = $config['params']['shop'];
    }

    /**
     * Load prepared data from mapper (shop)
     *
     * @return array
     */
    public function load() {
        return (new ShopModel($this->config['name'], $this->config['url'], $this->config['encoding'], $this->config['currency']))
            ->toArray();
    }
}