<?php
namespace Application\Modules\Prom\Lecabaret\Services\DataMappers;

use Application\Aware\Providers\Data;
use Application\Modules\Prom\Lecabaret\Models\ShopModel;
use Application\Exceptions\InternalServerErrorException;

/**
 * Class ShopMapper
 *
 * @package Application\Modules\Prom\Lecabaret\Services\DataMappers
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