<?php
namespace Application\Modules\Prom\Services;

use Application\Modules\Prom\Services\DataMappers\CatalogueMapper;
use Application\Modules\Prom\Models\ProductModel;

/**
 * Class ProductExportService
 *
 * @package Application\Modules\Prom\Service
 */
class ProductExportService {

    /**
     * Ready products collection
     *
     * @var array $productsCollection
     */
    private $productsCollection = [];

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

        $products = $this->catalogueMapper->loadProducts();
        $productCategories = $this->catalogueMapper->loadProductsCategories();

        $productsSplit = array_chunk($products, 50, true);

        foreach($productsSplit as $prod) {
            foreach($prod as $productId => $property) {

                $this->productsCollection[$productId] = (new ProductModel(
                    $productId,
                    $property['name'],
                    $property['available'],
                    $productCategories[$productId]['categoryId'],
                    $productCategories[$productId]['categoryName'],
                    $productCategories[$productId]['categoryTranslationId']
                    )
                )->load();
            }
        }

        var_dump($this->productsCollection); exit;

        //$productPrices = $this->catalogueMapper->loadProductsPrices($products);
        //$productPhotos = $this->catalogueMapper->loadProductsPhotos($products);
        //$productProperties = $this->catalogueMapper->loadProductsProperties($products);
        //$productDescription = $this->catalogueMapper->loadProductsDescription($products);
        //$productsSet = array_chunk($products, 50, true);

        //return $products;
    }
}