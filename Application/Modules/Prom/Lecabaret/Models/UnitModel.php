<?php
namespace Application\Modules\Prom\Lecabaret\Models;
use Application\Aware\Providers\Model;

/**
 * Class UnitModel
 *
 * @package Application\Modules\Prom\Lecabaret\Models
 */
class UnitModel extends Model {

    /**
     * Property attribute id
     *
     * @var int $propAttributeId
     */
    private $propAttributeId;

    /**
     * Unit name
     *
     * @var string $unitName
     */
    private $unitName = '';

    /**
     * Init model
     *
     * @param int $propAttributeId
     * @param string $unitName
     */
    public function __construct($propAttributeId, $unitName) {

        $this->setPropAttributeId($propAttributeId)
            ->setUnitName($unitName);
    }

    /**
     * Setup property attribute id "Weight"|"Length" ...
     *
     * @param int $propAttributeId
     * @return UnitModel
     */
    private function setPropAttributeId($propAttributeId) {

        $this->propAttributeId = (int)$propAttributeId;
        return $this;
    }

    /**
     * Unit name
     *
     * @see your counfig (develop|production) key => 'units'
     * @param string $unitName
     * @return UnitModel
     */
    private function setUnitName($unitName) {

        $this->unitName = $unitName;
        return $this;
    }

    /**
     * Property attribute
     *
     * @return int
     */
    public function getPropAttributeId() {
        return $this->propAttributeId;
    }

    /**
     * Unit name
     *
     * @return string
     */
    public function getUnitName() {
        return $this->unitName;
    }

    /**
     * Reverse object to real array for all public properties
     *
     * @return array
     */
    public function toArray() {
        return  get_object_vars($this);
    }
}