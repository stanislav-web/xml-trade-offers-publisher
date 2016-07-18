<?php
namespace Application\Modules\Prom\Lecabaret\Services\DataMappers;

use Application\Aware\Providers\Data;
use Application\Modules\Prom\Lecabaret\Models\UnitModel;
use Application\Exceptions\InternalServerErrorException;

/**
 * Class UnitMapper
 *
 * @package Application\Modules\Prom\Lecabaret\Services\DataMappers
 */
class UnitMapper extends Data {

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

        if(!isset($config['params']['units'])) {
            throw new InternalServerErrorException('Config attribute units undefined. See config production.php (development.php)');
        }
        $this->config = $config['params']['units'];
    }

    /**
     * Load prepared data from mapper (shop)
     *
     * @return array
     */
    public function load() {

        $data = [];

        foreach($this->config as $propAttributeId => $unitName) {

            $unitModel = new UnitModel($propAttributeId, $unitName);
            $data[$unitModel->getPropAttributeId()] = $unitModel->getUnitName();
        }

        return $data;
    }
}