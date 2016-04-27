<?php
namespace Application\Modules\Prom\Models;
use Application\Aware\Providers\Model;

/**
 * Class ProductDescriptionModel
 *
 * @package Application\Modules\Prom\Models
 */
class ProductDescriptionModel extends Model {

    /**
     * Product id
     *
     * @var int $productId
     */
    private $productId = 0;

    /**
     * Product description
     *
     * @var string $description
     */
    private $description = '';

    /**
     * Init
     *
     * @param int $productId
     * @param string $imgPaths
     * @param array $photos
     */
    public function __construct($productId, $description) {

        $this->setProductId($productId)
            ->setDescription($description);
    }

    /**
     * Validate product id
     *
     * @param int $productId
     * @return ProductDescriptionModel
     */
    public function setProductId($productId) {

        $this->productId = (int)$productId;
        return $this;
    }

    /**
     * Validate product description
     *
     * @param string $description
     * @return ProductDescriptionModel
     */
    public function setDescription($description) {

        $this->description = trim($description);
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