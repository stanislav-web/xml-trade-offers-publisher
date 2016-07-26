<?php
namespace Application\Modules\Prom\Nothingshop\Models;
use Application\Aware\Providers\Model;

/**
 * Class ProductPhotosModel
 *
 * @package Application\Modules\Prom\Nothingshop\Models
 */
class ProductPhotosModel extends Model {

    /**
     * Product id
     *
     * @var int $productId
     */
    private $productId = 0;

    /**
     * Img paths
     *
     * @var string $imgPaths
     */
    private $imgPaths = '';

    /**
     * Product photos
     *
     * @var array $photos
     */
    private $photos = [];

    /**
     * Init
     *
     * @param int $productId
     * @param string $imgPaths
     * @param array $photos
     */
    public function __construct($productId, $imgPaths, $photos) {

        $this->setProductId($productId)
            ->setImgPaths($imgPaths)
            ->setPhotos($photos);
    }

    /**
     * Validate product id
     *
     * @param int $productId
     * @return ProductPhotosModel
     */
    public function setProductId($productId) {

        $this->productId = (int)$productId;
        return $this;
    }

    /**
     * Validate image paths
     *
     * @param string $imgPaths
     * @return ProductPhotosModel
     */
    public function setImgPaths($imgPaths) {

        $this->imgPaths = trim($imgPaths);
        return $this;
    }

    /**
     * Validate product photos
     *
     * @param string $imgPaths
     * @return ProductPhotosModel
     */
    public function setPhotos($photos) {

        $photos = json_decode($photos, true);
        $photos = array_map(function($photo) {
            return sprintf($this->imgPaths, rand(0,9), $this->productId, trim($photo));
        },$photos);
        $this->photos = $photos;
        unset($this->imgPaths);
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