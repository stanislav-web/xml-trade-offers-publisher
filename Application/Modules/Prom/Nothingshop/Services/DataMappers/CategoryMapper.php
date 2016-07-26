<?php
namespace Application\Modules\Prom\Nothingshop\Services\DataMappers;

use Application\Aware\Providers\Data;
use Application\Services\Database;
use Application\Modules\Prom\Nothingshop\Models\CategoryModel;

/**
 * Class CategoryMapper
 *
 * @package Application\Modules\Prom\Nothingshop\Services\DataMappers
 */
class CategoryMapper extends Data {

    /**
     * Load categories query
     *
     * @const LOAD_CATEGORIES
     */
    const LOAD_CATEGORIES = '
      SELECT category.id AS categoryId, IF(category.parentId = 1, 0, category.parentId) AS parentId, category.name AS categoryName
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
     * Load prepared data from mapper (categories)
     *
     * @throws \Application\Exceptions\DbException
     *
     * @return array
     */
    public function load() {

        $data = [];

        $query = str_replace(':categories', implode(',', $this->config['params']['categories']), self::LOAD_CATEGORIES);
        $this->db->query($query);
        $categories = $this->db->fetchAll();

        foreach($categories as $category) {

            $categoryModel = new CategoryModel($category['categoryId'], $category['parentId'], $category['categoryName']);
            $data[$category['categoryId']] = $categoryModel->toArray();
        }

        return $data;
    }
}