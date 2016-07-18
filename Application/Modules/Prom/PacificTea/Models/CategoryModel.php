<?php
namespace Application\Modules\Prom\PacificTea\Models;
use Application\Aware\Providers\Model;

/**
 * Class CategoryModel
 *
 * @package Application\Modules\Prom\Models
 */
class CategoryModel extends Model {

    /**
     * Category id
     *
     * @var int $categoryId
     */
    private $categoryId = 0;

    /**
     * Category parent id
     *
     * @var int $parentId
     */
    private $parentId = 0;

    /**
     * Category name
     *
     * @var string $categoryName
     */
    private $categoryName = '';

    /**
     * Init model
     *
     * @param int $categoryId
     * @param int $parentId
     * @param string $categoryName
     */
    public function __construct($categoryId, $parentId, $categoryName) {

        $this->setCategoryId($categoryId)
            ->setParentId($categoryId)
            ->setCategoryName($categoryName);
    }

    /**
     * Validate category id
     *
     * @param int $categoryId
     * @return CategoryModel
     */
    private function setCategoryId($categoryId) {

        $this->categoryId = (int)$categoryId;
        return $this;
    }

    /**
     * Validate category parent id
     *
     * @param int $parentId
     * @return CategoryModel
     */
    private function setParentId($parentId) {

        $this->parentId = (int)$parentId;
        return $this;
    }

    /**
     * Validate category name
     *
     * @param string $categoryName
     * @return CategoryModel
     */
    private function setCategoryName($categoryName) {

        $this->categoryName = trim($categoryName);
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