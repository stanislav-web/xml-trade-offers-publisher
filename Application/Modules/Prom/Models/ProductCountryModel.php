<?php
namespace Application\Modules\Prom\Models;
use Application\Aware\Providers\Model;

/**
 * Class ProductCountryModel
 *
 * @package Application\Modules\Prom\Models
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
     * Product country translate id
     *
     * @var int $countryTranslateId
     */
    private $countryTranslateId = 0;

    /**
     * Init
     *
     * @param int $productId
     * @param string $imgPaths
     * @param array $photos
     */
    public function __construct($productId, $country, $countryTranslateId) {

        $this->setProductId($productId)
            ->setCountry($country)
            ->setCountryTranslateId($countryTranslateId);
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
     * Validate product country translate id
     *
     * @param string $countryTranslateId
     * @return ProductCountryModel
     */
    public function setCountryTranslateId($countryTranslateId) {

        $this->countryTranslateId = (int)$countryTranslateId;
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