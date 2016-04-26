<?php
namespace Application\Modules\Prom\Services\DataMappers;

use Application\Aware\Providers\Data;
use Application\Services\Database;
use Application\Modules\Prom\Models\CategoryModel;

/**
 * Class CategoryMapper
 *
 * @package Application\Modules\Prom\Services\DataMappers
 */
class CategoryMapper extends Data {

    /**
     * Load categories query
     *
     * @const LOAD_CATEGORIES
     */
    const LOAD_CATEGORIES = '
      SELECT category.id AS categoryId, IF(category.parentId = 1, 0, category.parentId) AS parentId, category.name AS categoryName,
        IFNULL(category.translationId, 0) AS categoryTranslationId
          FROM `attributes` AS category
            WHERE category.id IN (:categories) && category.type = \'category\'
    ';

    /**
     * Database service
     *
     * @var \Application\Services\Database $db
     */
    private $db = null;

    /**
     * Service config
     *
     * array $config
     */
    private $config = [];

    /**
     * Init data service
     *
     * @param array $config
     */
    public function __construct(array $config) {

        $this->config = $config;

        if(is_null($this->db) === true) {
            $this->db = new Database($this->config['db']);
        }
    }

    /**
     * Load categories
     *
     * @throws \Application\Exceptions\DbException
     *
     * @return array
     */
    public function loadCategories() {

        $data = [];

        $query = str_replace(':categories', implode(',', $this->config['params']['categories']), self::LOAD_CATEGORIES);
        $this->db->query($query);

        $categories = $this->db->fetchAll();

        foreach($categories as $category) {

            $categoryModel = new CategoryModel($category['categoryId'], $category['parentId'], $category['categoryName'], $category['categoryTranslationId']);
            $data[$category['categoryId']] = $categoryModel->toArray();
        }

        return $data;
    }
}