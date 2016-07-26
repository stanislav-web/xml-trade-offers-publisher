<?php
namespace Application\Modules\Prom\Nothingshop\Services;

use Application\Modules\Prom\Nothingshop\Services\DataMappers\CategoryMapper;
use Application\Modules\Prom\Nothingshop\Services\DataMappers\ProductCollectionMapper;
use Application\Modules\Prom\Nothingshop\Services\DataMappers\ProductMapper;
use Application\Modules\Prom\Nothingshop\Services\DataMappers\ShopMapper;

/**
 * Class CatalogueExportService
 *
 * @package Application\Modules\Prom\Nothingshop\Services
 */
class CatalogueExportService {

    /**
     * Shop data mapper
     *
     * @var \Application\Modules\Prom\Nothingshop\Services\DataMappers\ShopMapper $shopMapper
     */
    private $shopMapper = null;

    /**
     * Category data mapper
     *
     * @var \Application\Modules\Prom\Nothingshop\Services\DataMappers\CategoryMapper $categoryMapper
     */
    private $categoryMapper = null;

    /**
     * Product collection data mapper
     *
     * @var \Application\Modules\Prom\Nothingshop\Services\DataMappers\ProductCollectionMapper $productCollectionMapper
     */
    private $productCollectionMapper = null;

    /**
     * Product collection data mapper
     *
     * @var array $propertyMeasurementUnitsTrans
     */
    private $propertyMeasurementUnitsTrans = [];

    /**
     * Init connection
     *
     * @param array $config
     */
    public function __construct(array $config) {
        $this->shopMapper       = new ShopMapper($config);
        $this->categoryMapper   = new CategoryMapper($config);
        $this->productCollectionMapper    = new ProductCollectionMapper(new ProductMapper($config));
        $this->propertyMeasurementUnitsTrans = $config['params']['unitsTrans'];
    }

    /**
     * Format export data to output template
     *
     * @return array
     */
    public function exportData()
    {
        // Load Shop
        $shop = $this->shopMapper->load();

        // Load Categories
        $categories = $this->categoryMapper->load();

        // Load Products
        $products = $this->productCollectionMapper->load();

        $data = array_merge([
            'shop'          => $shop,
            'categories'    => $categories,
            'products'      => $products,
            'units'         => $this->propertyMeasurementUnitsTrans
        ]);
        return $data;
    }
}