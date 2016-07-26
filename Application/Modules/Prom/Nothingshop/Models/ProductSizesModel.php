<?php
namespace Application\Modules\Prom\Nothingshop\Models;
use Application\Aware\Providers\Model;

/**
 * Class ProductSizesModel
 *
 * @package Application\Modules\Prom\Nothingshop\Models
 */
class ProductSizesModel extends Model {

    /**
     * Product id
     *
     * @var int $productId
     */
    private $productId;

    /**
     * Product property variant id
     *
     * @var int $variantId
     */
    private $variantId = 0;

    /**
     * Product size name
     *
     * @var string $name
     */
    private $size = '';

    /**
     * Product size count
     *
     * @var int $value
     */
    private $count = 0;

    /**
     * Init
     * @param int $productId
     * @param int $variantId
     * @param string $size
     * @param int $count
     */
    public function __construct($productId, $variantId, $size, $count) {

        $this->setProductId($productId)
            ->setVariantId($variantId)
            ->setSize($size)
            ->setCount($count);
    }

    /**
     * Validate product id
     *
     * @param int $productId
     * @return ProductSizesModel
     */
    public function setProductId($productId) {

        $this->productId = (int)$productId;
        return $this;
    }

    /**
     * Validate product variant id
     *
     * @param int $variantId
     * @return ProductSizesModel
     */
    public function setVariantId($variantId) {

        $this->variantId = (int)$variantId;
        return $this;
    }

    /**
     * Validate product size name
     *
     * @param string $size
     * @return ProductSizesModel
     */
    public function setSize($size) {

        $this->size = trim($size);
        return $this;
    }

    /**
     * Validate product count
     *
     * @param int $count
     * @return ProductSizesModel
     */
    public function setCount($count) {

        $this->count = trim($count);
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