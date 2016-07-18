<?php
namespace Application\Modules\Prom\Lecabaret\Models;
use Application\Aware\Providers\Model;

/**
 * Class ProductModel
 *
 * @package Application\Modules\Prom\Lecabaret\Models
 */
class ProductModel extends Model {

    /**
     * Product id
     *
     * @var int $productId
     */
    private $productId = 0;

    /**
     * Product articul
     *
     * @var int $productArticul
     */
    private $productArticul = 0;

    /**
     * Product name
     *
     * @var string $productName
     */
    private $productName = '';

    /**
     * Product warehouse available
     *
     * @var int $available
     */
    private $available = 0;

    /**
     * Product properties
     *
     * @var array $properties
     */
    private $properties = [];

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
     * @param int $productArticul
     * @param string $productName
     * @param int $available
     * @param int $categoryId
     */
    public function __construct($productId, $productArticul, $productName, $available, $categoryId) {

        $this->setProductId($productId)
            ->setProductArticul($productArticul)
            ->setProductName($productName)
            ->setAvailable($available)
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
     * Validate product id
     *
     * @param int $productArticul
     * @return ProductModel
     */
    public function setProductArticul($productArticul) {

        $this->productArticul = (int)$productArticul;
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
     * Validate product available
     *
     * @param int $available
     * @return ProductModel
     */
    public function setAvailable($available) {

        $this->available = (int)$available;
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