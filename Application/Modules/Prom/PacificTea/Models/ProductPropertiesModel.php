<?php
namespace Application\Modules\Prom\PacificTea\Models;
use Application\Aware\Providers\Model;

/**
 * Class ProductPropertiesModel
 *
 * @package Application\Modules\Prom\Models
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
     * Init
     * @param int $productId
     * @param int $attributeId
     * @param int $variantId
     * @param string $name
     * @param string|int $value
     */
    public function __construct($productId, $attributeId, $variantId, $name, $value) {

        //$this->setProductId($productId)
            $this->setAttributeId($attributeId)
            ->setVariantId($variantId)
            ->setName($name)
            ->setValue($value);
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
     * Reverse object to real array for all public properties
     *
     * @return array
     */
    public function toArray() {
        return  get_object_vars($this);
    }
}