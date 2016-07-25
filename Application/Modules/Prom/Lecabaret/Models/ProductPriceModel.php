<?php
namespace Application\Modules\Prom\Lecabaret\Models;
use Application\Aware\Providers\Model;

/**
 * Class ProductPriceModel
 *
 * @package Application\Modules\Prom\Lecabaret\Models
 */
class ProductPriceModel extends Model {

    /**
     * Product id
     *
     * @var int $productId
     */
    private $productId = 0;

    /**
     * Product price
     *
     * @var float $price
     */
    private $price = 0;

    /**
     * Product price discount percent
     *
     * @var int $discountPercent
     */
    private $discountPercent = 0;

    /**
     * Product price discount value
     *
     * @var float $discountPercent
     */
    private $discountValue = 0;

    /**
     * Product price currency id
     *
     * @var int $currencyId
     */
    private $currencyId = 0;

    /**
     * Product price currency name
     *
     * @var string $currencyName
     */
    private $currencyName = '';

    /**
     * Init
     *
     * @param int $productId
     * @param float $price
     * @param int $discountPercent
     * @param float $discountValue
     * @param int $currencyId
     * @param string $currencyName
     */
    public function __construct($productId, $price, $discountPercent, $discountValue, $currencyId, $currencyName) {

        $this->setProductId($productId)
            ->setPrice($price)
            ->setDiscountPercent($discountPercent)
            ->setDiscountValue($discountValue)
            ->setCurrencyId($currencyId)
            ->setCurrencyName($currencyName);
    }

    /**
     * Validate product id
     *
     * @param int $productId
     * @return ProductPriceModel
     */
    public function setProductId($productId) {

        $this->productId = (int)$productId;
        return $this;
    }

    /**
     * Validate product price
     *
     * @param float $price
     * @return ProductPriceModel
     */
    public function setPrice($price) {

        $this->price = number_format($price, 2);
        return $this;
    }

    /**
     * Validate product discount percent
     *
     * @param int $discountPercent
     * @return ProductPriceModel
     */
    public function setDiscountPercent($discountPercent) {

        $this->discountPercent = (int)$discountPercent;
        return $this;
    }

    /**
     * Validate product discount value
     *
     * @param int $discountValue
     * @return ProductPriceModel
     */
    public function setDiscountValue($discountValue) {

        if (0 < $discountValue) {
            $this->discountValue = number_format($discountValue, 2);
        }
        return $this;
    }

    /**
     * Validate currency id
     *
     * @param int $currencyId
     * @return ProductPriceModel
     */
    public function setCurrencyId($currencyId) {

        $this->currencyId = (int)$currencyId;
        return $this;
    }

    /**
     * Validate currency name
     *
     * @param string $currencyName
     * @return ProductPriceModel
     */
    public function setCurrencyName($currencyName) {

        $this->currencyName = trim($currencyName);
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