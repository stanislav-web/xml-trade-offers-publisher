<?php
namespace Application\Modules\Prom\Models;

/**
 * Class ProductModel
 *
 * @package Application\Modules\Prom\Models
 */
class ProductModel {

    private $product = [];

    /**
     * @param $productId
     */
    public function __construct($productId, $productName, $count, $categoryId, $categoryName, $categoryTranslationId) {

        $this->product['productId'] = (int)$productId;
        $this->product['productName'] = trim($productName);
        $this->product['productCount'] = (int)$count;
        $this->product['categoryId'] = (int)$categoryId;
        $this->product['categoryName'] = trim($categoryName);
        $this->product['categoryTranslationId'] = (int)$categoryTranslationId;
    }

    public function load() {
        return $this->product;
    }
}