<?php
namespace Application\Modules\Prom\Services;

use Application\Modules\Prom\Services\DataMappers\CategoryMapper;
use Application\Modules\Prom\Services\DataMappers\ProductCollectionMapper;
use Application\Modules\Prom\Services\DataMappers\ProductMapper;
use Application\Modules\Prom\Services\DataMappers\ShopMapper;

/**
 * Class CatalogueExportService
 *
 * @package Application\Modules\Prom\Service
 */
class CatalogueExportService {

    /**
     * Shop data mapper
     *
     * @var \Application\Modules\Prom\Services\DataMappers\ShopMapper $shopMapper
     */
    private $shopMapper = null;

    /**
     * Category data mapper
     *
     * @var \Application\Modules\Prom\Services\DataMappers\CategoryMapper $categoryMapper
     */
    private $categoryMapper = null;

    /**
     * Product collection data mapper
     *
     * @var \Application\Modules\Prom\Services\DataMappers\ProductCollectionMapper $productCollectionMapper
     */
    private $productCollectionMapper = null;


    /**
     * Init connection
     *
     * @param array $config
     */
    public function __construct(array $config) {
        $this->shopMapper       = new ShopMapper($config);
        $this->categoryMapper   = new CategoryMapper($config);
        $this->productCollectionMapper    = new ProductCollectionMapper(new ProductMapper($config));
    }

    /**
     * Format export data to output template
     *
     * @return array
     */
    public function exportData() {

        // Load Shop data
        $shop = $this->shopMapper->load();
        print_r($shop);

        $categories = $this->categoryMapper->load();
        print_r($categories);

        $products = $this->productCollectionMapper->load();
        print_r($products);


    }
}