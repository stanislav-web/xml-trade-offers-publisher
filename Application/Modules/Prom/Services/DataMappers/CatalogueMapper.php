<?php
namespace Application\Modules\Prom\Services\DataMappers;

use Application\Aware\Providers\Data;
use Application\Services\Database;

/**
 * Class CatalogueMapper
 *
 * @package Application\Modules\Prom\Services\DataMappers
 */
class CatalogueMapper extends Data {

    /**
     * Load products query
     *
     * @const LOAD_PRODUCTS
     */
    const LOAD_PRODUCTS = '
      SELECT product.id, product.name, SUM(storage.`count`) AS `available`
        FROM products AS product
          INNER JOIN `productCategories` AS category ON (category.`productId` = product.id)
          INNER JOIN `productsInStock` AS storage ON (storage.`productId` = product.id && storage.count > 0)
            WHERE category.attributeId IN (:categories) && storage.`warehouseId` IN (:warehouses)
              GROUP BY product.id ORDER BY product.id';

    /**
     * Load product's categories query
     *
     * @const LOAD_PRODUCTS_CATEGORIES
     */
    const LOAD_PRODUCTS_CATEGORIES = '
      SELECT product.id AS productId, category.attributeId AS categoryId, attr.name AS categoryName, IFNULL(attr.translationId, 0) AS categoryTranslationId
        FROM products AS product
          INNER JOIN `productCategories` AS category ON (category.`productId` = product.id)
          INNER JOIN `attributes` AS attr ON (attr.`id` = category.`attributeId`)
            WHERE category.attributeId IN (:categories) && attr.type = \'category\'';

    /**
     * Load product's prices query
     *
     * @const LOAD_PRODUCTS_PRICES
     */
    const LOAD_PRODUCTS_PRICES = '
      SELECT pprice.productId, pprice.value AS price, pprice.discount AS percent, pprice.discountValue AS discount, curr.name
	    FROM `productPrices` AS pprice
	      INNER JOIN `prices` AS price ON (price.id = pprice.`attributeId` && price.id = :priceId)
	      INNER JOIN `currencies` AS curr ON (curr.id = price.`currencyId`)
	        WHERE pprice.`productId` IN (:productIds)';

    /**
     * Load product's photos query
     *
     * @const LOAD_PRODUCTS_PHOTOS
     */
    const LOAD_PRODUCTS_PHOTOS = '
      SELECT prop.productId, prop.value AS photos
	    FROM `productMarketingProperties` AS prop
	    WHERE prop.`attributeId` = :photosId && prop.`productId` IN (:productIds)
	';

    /**
     * Service config
     *
     * array $config
     */
    private $config = [];

    /**
     * Database service
     *
     * @var \Application\Services\Database $db
     */
    private $db = null;

    /**
     * Init data service
     *
     * @param array $config
     */
    public function __construct(array $config) {

        $this->config = $config;

        if(is_null($this->db) === true) {
            $this->db = new Database($this->config['db']['catalogue']);
        }
    }

    /**
     * Load products
     *
     * @throws \Application\Exceptions\DbException
     *
     * @return array
     */
    public function loadProducts() {

        $this->db->query(self::LOAD_PRODUCTS);
        $this->db->bind(':categories', $this->config['params']['categories']);
        $this->db->bind(':warehouses', $this->config['params']['warehouses']);
        return $this->arraySetKey($this->db->fetchAll(), 'id');
    }

    /**
     * Load product's categories
     *
     * @throws \Application\Exceptions\DbException
     *
     * @return array
     */
    public function loadProductsCategories() {

        $this->db->query(self::LOAD_PRODUCTS_CATEGORIES);
        $this->db->bind(':categories', $this->config['params']['categories']);
        return $this->arraySetKey($this->db->fetchAll(), 'productId');
    }

    /**
     * Load product's categories
     *
     * @throws \Application\Exceptions\DbException
     *
     * @return array
     */
    public function loadProductsPrices(array $productIds) {

        $this->db->query(self::LOAD_PRODUCTS_PRICES);
        $this->db->bind(':productIds', $productIds);
        $this->db->bind(':priceId', $this->config['params']['priceId']);

        return $this->arraySetKey($this->db->fetchAll(), 'productId');
    }

    /**
     * Load product's photos
     *
     * @throws \Application\Exceptions\DbException
     *
     * @return array
     */
    public function loadProductsPhotos(array $productIds) {

        $this->db->query(self::LOAD_PRODUCTS_PHOTOS);
        $this->db->bind(':productIds', $productIds);
        $this->db->bind(':photosId', $this->config['params']['photosId']);

        return $this->arraySetKey($this->db->fetchAll(), 'productId');
    }


}