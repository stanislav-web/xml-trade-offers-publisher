<?php
namespace Application\Modules\Prom\Nothingshop\Models;
use Application\Aware\Providers\Model;

/**
 * Class ProductCountryModel
 *
 * @package Application\Modules\Prom\Nothingshop\Models
 */
class ProductCountryModel extends Model {

    /**
     * Product id
     *
     * @var int $productId
     */
    private $productId = 0;

    /**
     * Product country
     *
     * @var string $country
     */
    private $country = '';

    /**
     * Init
     *
     * @param int $productId
     * @param string $country
     */
    public function __construct($productId, $country) {

        $this->setProductId($productId)
            ->setCountry($country);
    }

    /**
     * Validate product id
     *
     * @param int $productId
     * @return ProductCountryModel
     */
    public function setProductId($productId) {

        $this->productId = (int)$productId;
        return $this;
    }

    /**
     * Validate product country
     *
     * @param string country
     * @return ProductCountryModel
     */
    public function setCountry($country) {

        $this->country = trim($country);
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