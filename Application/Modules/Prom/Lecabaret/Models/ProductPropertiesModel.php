<?php
namespace Application\Modules\Prom\Lecabaret\Models;
use Application\Aware\Providers\Model;

/**
 * Class ProductPropertiesModel
 *
 * @package Application\Modules\Prom\Lecabaret\Models
 */
class ProductPropertiesModel extends Model {

    /**
     * Product id
     *
     * @var int $productId
     */
    //private $productId = 0;

    /**
     * Product property attribute id
     *
     * @var int $attributeId
     */
    private $attributeId = 0;

    /**
     * Product property variant id
     *
     * @var int $variantId
     */
    private $variantId = 0;

    /**
     * Product property name
     *
     * @var string $name
     */
    private $name = '';

    /**
     * Product property value
     *
     * @var string|int $value
     */
    private $value = null;

    /**
     * Product property unit (gr, sm dm3)
     *
     * @var string $unit
     */
    private $unit = '';

    /**
     * Init
     * @param int $productId
     * @param int $attributeId
     * @param int $variantId
     * @param string $name
     * @param string|int $value
     * @param string $unit
     */
    public function __construct($productId, $attributeId, $variantId, $name, $value, $unit) {

        //$this->setProductId($productId)
            $this->setAttributeId($attributeId)
            ->setVariantId($variantId)
            ->setName($name)
            ->setValue($value)
            ->setUnit($unit);
    }

    /**
     * Validate product id
     *
     * @param int $productId
     * @return ProductPropertiesModel
     */
//    public function setProductId($productId) {
//
//        $this->productId = (int)$productId;
//        return $this;
//    }

    /**
     * Validate product attribute id
     *
     * @param int $attributeId
     * @return ProductPropertiesModel
     */
    public function setAttributeId($attributeId) {

        $this->attributeId = (int)$attributeId;
        return $this;
    }

    /**
     * Validate product variant id
     *
     * @param int $variantId
     * @return ProductPropertiesModel
     */
    public function setVariantId($variantId) {

        $this->variantId = (int)$variantId;
        return $this;
    }

    /**
     * Validate product property name
     *
     * @param string $name
     * @return ProductPropertiesModel
     */
    public function setName($name) {

        $this->name = trim($name);
        return $this;
    }

    /**
     * Validate product property value
     *
     * @param int|string $value
     * @return ProductPropertiesModel
     */
    public function setValue($value) {

        $this->value = trim($value);
        return $this;
    }

    /**
     * Validate product property unit
     *
     * @param string $unit
     * @return ProductPropertiesModel
     */
    public function setUnit($unit) {

        $this->unit = trim($unit);
        return $this;
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