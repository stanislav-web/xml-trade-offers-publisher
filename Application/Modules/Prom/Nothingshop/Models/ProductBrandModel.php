<?php
namespace Application\Modules\Prom\Nothingshop\Models;
use Application\Aware\Providers\Model;

/**
 * Class ProductBrandModel
 *
 * @package Application\Modules\Prom\Nothingshop\Models
 */
class ProductBrandModel extends Model {

    /**
     * Product id
     *
     * @var int $productId
     */
    private $productId = 0;

    /**
     * Product brand
     *
     * @var string $brand
     */
    private $brand = '';

    /**
     * Init
     *
     * @param int $productId
     * @param string $brand
     */
    public function __construct($productId, $brand) {

        $this->setProductId($productId)
            ->setBrand($brand);
    }

    /**
     * Validate product id
     *
     * @param int $productId
     * @return ProductBrandModel
     */
    public function setProductId($productId) {

        $this->productId = (int)$productId;
        return $this;
    }

    /**
     * Validate product brand
     *
     * @param string $brand
     * @return ProductBrandModel
     */
    public function setBrand($brand) {

        $this->brand = trim($brand);
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