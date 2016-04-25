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


    public function loadExportData() {

        //$products = $this->catalogueMapper->loadProducts();
        //$productCategories = $this->loadProductsCategories();
        //$productPrices = $this->loadProductsPrices($products);
        //$productPhotos = $this-> loadProductsPhotos($products);
        //$productsSet = array_chunk($products, 50, true);

        //return $products;
    }
}