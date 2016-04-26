<?php
namespace Application\Modules\Prom\Services\DataMappers;

use Application\Aware\Providers\Data;
use Application\Services\Database;

/**
 * Class ProductMapper
 *
 * @package Application\Modules\Prom\Services\DataMappers
 */
class ProductMapper extends Data {

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
     * Load product's photos query
     *
     * @const LOAD_PRODUCTS_PROPERTIES
     */
    const LOAD_PRODUCTS_PROPERTIES = '
      SELECT prod.`id`, prop.`attributeId`, prop.`variantId`, attr.`name`, prop.`value`, IFNULL(attr.`translationId`, 0) AS propNameTranslationId, IFNULL(prop.`translationId`, 0) AS propValueTranslationId
	    FROM `productProperties` AS prop
		  INNER JOIN products AS prod ON (prod.id = prop.`productId`)
		  INNER JOIN attributes AS attr ON (attr.id = prop.`attributeId`)
		  LEFT JOIN translations AS trans ON (trans.`translationId` = attr.`translationId` && trans.`translationId` = prop.`translationId`&& trans.`languageId` = :languageId)
		    WHERE prop.`value` != \'\' && prop.`value` != \'-\' && prop.`attributeId` NOT IN (:excludeAttributes) && prop.`productId` IN(:productIds)

      UNION ALL SELECT prod.`id`, mark.`attributeId`, mark.`variantId`, attr.name, mark.`value`, IFNULL(attr.`translationId`, 0) AS attrTranslationId, 0 AS propTranslationId
	    FROM `productMarketingProperties` AS mark
		  INNER JOIN products AS prod ON (prod.id = mark.`productId`)
		  INNER JOIN attributes AS attr ON (attr.id = mark.`attributeId`)
		  LEFT JOIN translations AS trans ON (trans.`translationId` = attr.`translationId` && trans.`languageId` = :languageId)
		    WHERE mark.`value` != \'\' && mark.`attributeId` NOT IN (:excludeAttributes) && mark.`productId` IN(:productIds)
	ORDER BY 1 ASC;
    ';

    /**
     * Load product's description
     *
     * @const LOAD_PRODUCTS_DESCRIPTION
     */
    const LOAD_PRODUCTS_DESCRIPTION = '
      SELECT prod.`id` AS productId, prop.`attributeId`, attr.`name`, prop.`value` AS description, IFNULL(attr.`translationId`, 0) AS propNameTranslationId, IFNULL(prop.`translationId`, 0) AS propValueTranslationId
	    FROM `productProperties` AS prop
		  INNER JOIN products AS prod ON (prod.id = prop.`productId`)
		  INNER JOIN attributes AS attr ON (attr.id = prop.`attributeId`)
		  LEFT JOIN translations AS trans ON (trans.`translationId` = attr.`translationId` && trans.`translationId` = prop.`translationId`&& trans.`languageId` = 1)
		  WHERE prop.`attributeId` IN (:descriptionId) && prop.`productId` IN(:productIds)

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
            $this->db = new Database($this->config['db']);
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

    /**
     * Load product's properties
     *
     * @throws \Application\Exceptions\DbException
     *
     * @return array
     */
    public function loadProductsProperties(array $productIds) {

        $this->db->query(self::LOAD_PRODUCTS_PROPERTIES);
        $this->db->bind(':productIds', $productIds);
        $this->db->bind(':languageId', $this->config['params']['languageId']);
        $this->db->bind(':excludeAttributes', $this->config['params']['excludeAttributes']);

        return $this->arraySetKey($this->db->fetchAll(), 'productId');
    }

    /**
     * Load product's description
     *
     * @throws \Application\Exceptions\DbException
     *
     * @return array
     */
    public function loadProductsDescription(array $productIds) {

        $this->db->query(self::LOAD_PRODUCTS_DESCRIPTION);
        $this->db->bind(':productIds', $productIds);
        $this->db->bind(':descriptionId', $this->config['params']['descriptionId']);

        return $this->arraySetKey($this->db->fetchAll(), 'productId');
    }


}