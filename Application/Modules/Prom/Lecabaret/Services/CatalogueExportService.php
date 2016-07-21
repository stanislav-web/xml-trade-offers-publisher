<?php
namespace Application\Modules\Prom\Lecabaret\Services;

use Application\Modules\Prom\Lecabaret\Services\DataMappers\CategoryMapper;
use Application\Modules\Prom\Lecabaret\Services\DataMappers\ProductCollectionMapper;
use Application\Modules\Prom\Lecabaret\Services\DataMappers\ProductMapper;
use Application\Modules\Prom\Lecabaret\Services\DataMappers\ShopMapper;

/**
 * Class CatalogueExportService
 *
 * @package Application\Modules\Prom\Lecabaret\Services
 */
class CatalogueExportService {

    /**
     * Shop data mapper
     *
     * @var \Application\Modules\Prom\Lecabaret\Services\DataMappers\ShopMapper $shopMapper
     */
    private $shopMapper = null;

    /**
     * Category data mapper
     *
     * @var \Application\Modules\Prom\Lecabaret\Services\DataMappers\CategoryMapper $categoryMapper
     */
    private $categoryMapper = null;

    /**
     * Product collection data mapper
     *
     * @var \Application\Modules\Prom\Lecabaret\Services\DataMappers\ProductCollectionMapper $productCollectionMapper
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
        ]);
        return $data;
    }
}