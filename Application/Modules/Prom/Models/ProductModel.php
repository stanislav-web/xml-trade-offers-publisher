<?php
namespace Application\Modules\Prom\Models;
use Application\Aware\Providers\Model;

/**
 * Class CategoryModel
 *
 * @package Application\Modules\Prom\Models
 */
class ProductModel extends Model {

    /**
     * Product id
     *
     * @var int $productId
     */
    private $productId = 0;

    /**
     * Product name
     *
     * @var string $productName
     */
    private $productName = '';

    /**
     * Product warehouse available
     *
     * @var int $count
     */
    private $count = 0;

    /**
     * Product category id
     *
     * @var int $categoryId
     */
    private $categoryId = 0;

    /**
     * Init model
     *
     * @param int $productId
     * @param string $productName
     * @param int $count
     * @param int $categoryId
     */
    public function __construct($productId, $productName, $count, $categoryId) {

        $this->setProductId($productId)
            ->setProductName($productName)
            ->setCount($count)
            ->setCategoryId($categoryId);
    }

    /**
     * Validate product id
     *
     * @param int $productId
     * @return ProductModel
     */
    public function setProductId($productId) {

        $this->productId = (int)$productId;
        return $this;
    }

    /**
     * Validate product name
     *
     * @param string $productName
     * @return ProductModel
     */
    public function setProductName($productName) {

        $this->productName = trim($productName);
        return $this;
    }

    /**
     * Validate product count
     *
     * @param int $count
     * @return ProductModel
     */
    public function setCount($count) {

        $this->count = $count;
        return $this;
    }

    /**
     * Validate category id
     *
     * @param int $categoryId
     * @return ProductModel
     */
    public function setCategoryId($categoryId) {

        $this->categoryId = (int)$categoryId;
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