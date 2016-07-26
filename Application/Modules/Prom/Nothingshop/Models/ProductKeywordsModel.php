<?php
namespace Application\Modules\Prom\Nothingshop\Models;
use Application\Aware\Providers\Model;

/**
 * Class ProductKeywordsModel
 *
 * @package Application\Modules\Prom\Nothingshop\Models
 */
class ProductKeywordsModel extends Model {

    /**
     * Product id
     *
     * @var int $productId
     */
    private $productId = 0;

    /**
     * Product keywords
     *
     * @var string $keywords
     */
    private $keywords = '';

    /**
     * Init
     *
     * @param int $productId
     * @param string $keywords
     */
    public function __construct($productId, $keywords) {

        $this->setProductId($productId)
            ->setKeywords($keywords);
    }

    /**
     * Validate product id
     *
     * @param int $productId
     * @return ProductKeywordsModel
     */
    public function setProductId($productId) {

        $this->productId = (int)$productId;
        return $this;
    }

    /**
     * Validate product keywords
     *
     * @param string $keywords
     * @return ProductKeywordsModel
     */
    public function setKeywords($keywords) {

        $this->keywords = trim($keywords);
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