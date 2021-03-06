<?php
namespace Application\Modules\Prom\Nothingshop\Services\DataMappers;

use Application\Aware\Providers\Data;
use Application\Exceptions\DbException;
use Application\Exceptions\NotFoundException;
use Application\Services\Database;

/**
 * Class ProductMapper
 *
 * @package Application\Modules\Prom\Nothingshop\Services\DataMappers
 */
class ProductMapper extends Data {

    /**
     * Products separate chunk parts
     *
     * @const PRODUCTS_CHUNK_MAP
     */
    const PRODUCTS_CHUNK_MAP = 40;

    /**
     * Load products query
     *
     * @const LOAD_PRODUCTS
     */
    const LOAD_PRODUCTS = '
      SELECT product.id, product.articul, product.name, SUM(storage.`count`) AS `available`, category.attributeId AS categoryId
        FROM products AS product
          INNER JOIN `productCategories` AS category ON (category.`productId` = product.id)
          INNER JOIN `productsInStock` AS storage ON (storage.`productId` = product.id && storage.count > 0)
              WHERE category.attributeId IN (:categories)
              && storage.`warehouseId` IN (:warehouses)
              GROUP BY product.id ORDER BY product.id';

    /**
     * Load products country query
     *
     * @const LOAD_PRODUCTS_COUNTRY
     */
    const LOAD_PRODUCTS_COUNTRY = '
        SELECT product.id AS productId, attr.`name` AS country
          FROM products AS product
            INNER JOIN `productCategories` AS category ON (category.`productId` = product.id)
            INNER JOIN attributes attr ON (attr.id = category.`attributeId` && attr.`parentId` = :countryId)
              WHERE  product.id IN (:productIds)
                GROUP BY product.id
    ';

    /**
     * Load products sizes query
     *
     * @const LOAD_PRODUCTS_SIZES
     */
    const LOAD_PRODUCTS_SIZES = '
        SELECT storage.productId, storage.variantId, attr.name AS `size`, `count`
          FROM productsInStock AS storage
            LEFT JOIN attributes AS attr ON (storage.variantId = attr.id && attr.parentId = 11)
              WHERE storage.productId IN(:productIds)
              GROUP BY productId, variantId;
    ';

    /**
     * Load product's prices query
     *
     * @const LOAD_PRODUCTS_PRICES
     */
    const LOAD_PRODUCTS_PRICES = '
      SELECT pprice.productId, pprice.value AS price, pprice.discount AS percent, pprice.discountValue, curr.id AS currencyId, curr.name AS currencyName
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
      SELECT prod.`id` AS productId, prop.`attributeId`, prop.`variantId`, attr.`name` AS name, prop.`value` AS value, units.name AS unit
	    FROM `productProperties` AS prop
		  INNER JOIN products AS prod ON (prod.id = prop.`productId`)
		  INNER JOIN attributes AS attr ON (attr.id = prop.`attributeId`)
		  LEFT JOIN measurementUnits AS units ON (units.id = attr.`measurementUnitId` && attr.`measurementUnitId`)

		 WHERE prop.`value` != \'\' && prop.`value` != \'-\' && prop.`value` != 0 && prop.`attributeId` NOT IN (:excludeAttributes) && prop.`productId` IN(:productIds)
         GROUP BY prod.`id`, prop.`attributeId`
      UNION ALL SELECT prod.`id`, mark.`attributeId`, mark.`variantId`, attr.`name` AS name, mark.`value`, null
	    FROM `productMarketingProperties` AS mark
		  INNER JOIN products AS prod ON (prod.id = mark.`productId`)
		  INNER JOIN attributes AS attr ON (attr.id = mark.`attributeId`)
		    WHERE mark.`value` != \'\' && mark.`attributeId` NOT IN (:excludeAttributes) && mark.`productId` IN(:productIds)
    ';

    /**
     * Load product's description
     *
     * @const LOAD_PRODUCTS_DESCRIPTION
     */
    const LOAD_PRODUCTS_DESCRIPTION = '
      SELECT prod.`id` AS productId, prop.`value` AS description
	    FROM `productProperties` AS prop
		  INNER JOIN products AS prod ON (prod.id = prop.`productId`)
		  INNER JOIN attributes AS attr ON (attr.id = prop.`attributeId`)
		  WHERE prop.`attributeId` IN (:descriptionId) && prop.`productId` IN(:productIds)
    ';

    /**
     * Load product's brand
     *
     * @const LOAD_PRODUCTS_BRAND
     */
    const LOAD_PRODUCTS_BRAND = '
      SELECT prod.`id` AS productId, attr.name AS brand
	    FROM `productCategories` AS prop
		  INNER JOIN products AS prod ON (prod.id = prop.`productId`)
		  INNER JOIN attributes AS attr ON (attr.id = prop.`attributeId` && attr.`type` = \'category\')
		  WHERE attr.`parentId` = :brandId && prop.`productId` IN(:productIds)
    ';

    /**
     * Load product's keywords
     *
     * @const LOAD_PRODUCTS_KEYWORDS
     */
    const LOAD_PRODUCTS_KEYWORDS = '
      SELECT prod.id AS productId, GROUP_CONCAT(DISTINCT attr.name) AS keyword
        FROM products AS  prod
          LEFT JOIN `productCategories` AS cat ON (cat.`productId` = prod.id)
          LEFT JOIN `attributes` AS attr ON (attr.`id` = cat.`attributeId` && attr.`type` = \'category\')
          WHERE prod.id IN (:productIds)
          GROUP BY productId
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
     * Load product data
     * @return null
     */
    public function load() {}

    /**
     * Load service config data
     *
     * @return object
     */
    public function loadConfig() {
        return (object)$this->config;
    }

    /**
     * Load products
     *
     * @throws \Application\Exceptions\DbException
     * @throws \Application\Exceptions\NotFoundException
     *
     * @return array
     */
    public function loadProducts() {

        $query = str_replace(':categories', implode(',', $this->config['params']['categories']), self::LOAD_PRODUCTS);
        $query = str_replace(':warehouses', implode(',', $this->config['params']['warehouses']), $query);

        try {
            $data = $this->db->query($query)->fetchAll();
            if($data === false) {
                throw new NotFoundException('Products not found for configuration\'s criteria');
            }
        }
        catch(\PDOException $e) {
            throw new DbException($e->getMessage());
        }
        return $this->arraySetKey($data, 'id');
    }

    /**
     * Load product's categories
     *
     * @throws \Application\Exceptions\DbException
     *
     * @return array
     */
    public function loadProductsPrices(array $productIds) {

        $query = str_replace(':productIds', implode(',', $productIds), self::LOAD_PRODUCTS_PRICES);
        $this->db->query($query);
        $this->db->bind(':priceId', $this->config['params']['priceId']);
        $data = $this->db->fetchAll();

        if($data === false) {
            throw new NotFoundException('Products prices not found');
        }
        return $this->arraySetKey($data, 'productId');
    }

    /**
     * Load product's photos
     *
     * @throws \Application\Exceptions\DbException
     *
     * @return array
     */
    public function loadProductsPhotos(array $productIds) {

        $query = str_replace(':productIds', implode(',', $productIds), self::LOAD_PRODUCTS_PHOTOS);
        $this->db->query($query);
        $this->db->bind(':photosId', $this->config['params']['photosId']);
        $data = $this->db->fetchAll();

        if($data === false) {
            throw new NotFoundException('Products photos not found');
        }
        return $this->arraySetKey($data, 'productId');
    }

    /**
     * Load product's description
     *
     * @throws \Application\Exceptions\DbException
     *
     * @return array
     */
    public function loadProductsDescription(array $productIds) {

        $query = str_replace(':productIds', implode(',', $productIds), self::LOAD_PRODUCTS_DESCRIPTION);

        $this->db->query($query);
        $this->db->bind(':descriptionId', $this->config['params']['descriptionId']);

        return $this->arraySetKey($this->db->fetchAll(), 'productId');
    }

    /**
     * Load product's brands
     *
     * @throws \Application\Exceptions\DbException
     *
     * @return array
     */
    public function loadProductsBrands(array $productIds) {

        $query = str_replace(':productIds', implode(',', $productIds), self::LOAD_PRODUCTS_BRAND);
        $this->db->query($query);
        $this->db->bind(':brandId', $this->config['params']['brandId']);

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

        $query = str_replace(':productIds', implode(',', $productIds), self::LOAD_PRODUCTS_PROPERTIES);
        $query = str_replace(':excludeAttributes', implode(',', $this->config['params']['excludeAttributes']), $query);

        $this->db->query($query);
        return $this->db->fetchAll();
    }

    /**
     * Load product's keywords from properties
     *
     * @throws \Application\Exceptions\DbException
     *
     * @return array
     */
    public function loadProductsKeywords(array $productIds) {

        $query = str_replace(':productIds', implode(',', $productIds), self::LOAD_PRODUCTS_KEYWORDS);

        $this->db->query($query);

        return $this->arraySetKey($this->db->fetchAll(), 'productId');
    }

    /**
     * Load product's country
     *
     * @throws \Application\Exceptions\DbException
     *
     * @return array
     */
    public function loadProductsCountry(array $productIds) {

        $query = str_replace(':productIds', implode(',', $productIds), self::LOAD_PRODUCTS_COUNTRY);

        $this->db->query($query);
        $this->db->bind(':countryId', $this->config['params']['countryId']);

        return $this->arraySetKey($this->db->fetchAll(), 'productId');
    }

    /**
     * Load product's sizes
     *
     * @throws \Application\Exceptions\DbException
     *
     * @return array
     */
    public function loadProductsSizes(array $productIds) {

        $query = str_replace(':productIds', implode(',', $productIds), self::LOAD_PRODUCTS_SIZES);

        $this->db->query($query);

        return $this->db->fetchAll();
    }

}