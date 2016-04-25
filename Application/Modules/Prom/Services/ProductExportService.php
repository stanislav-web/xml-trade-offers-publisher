<?php
namespace Application\Modules\Prom\Services;

use Application\Modules\Prom\Services\DataMappers\CatalogueMapper;

/**
 * Class ProductExportService
 *
 * @package Application\Modules\Prom\Service
 */
class ProductExportService {

    /**
     * Catalogue data mapper
     *
     * @var \Application\Modules\Prom\Services\DataMappers\CatalogueMapper $catalogueMapper
     */
    private $catalogueMapper = null;

    /**
     * Init connection
     *
     * @param array $config
     */
    public function __construct(array $config) {
        $this->catalogueMapper = new CatalogueMapper($config);
    }

    /**
     * Format export data to output template
     *
     * @return array
     */
    public function loadExportData() {

        //$products = $this->catalogueMapper->loadProducts();
        //$productCategories = $this->catalogueMapper->loadProductsCategories();
        //$productPrices = $this->catalogueMapper->loadProductsPrices($products);
        //$productPhotos = $this->catalogueMapper->loadProductsPhotos($products);
        //$productProperties = $this->catalogueMapper->loadProductsProperties($products);
        //$productDescription = $this->catalogueMapper->loadProductsDescription($products);
        //$productsSet = array_chunk($products, 50, true);

        //return $products;
    }
}