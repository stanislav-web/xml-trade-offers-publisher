<?php
namespace Application\Modules\Prom\Services;

use Application\Modules\Prom\Services\DataMappers\CategoryMapper;
use Application\Modules\Prom\Services\DataMappers\ProductMapper;
use Application\Modules\Prom\Models\ProductModel;
use Application\Modules\Prom\Services\DataMappers\ShopMapper;

/**
 * Class CatalogueExportService
 *
 * @package Application\Modules\Prom\Service
 */
class CatalogueExportService {

    /**
     * Ready products collection
     *
     * @var array $productsCollection
     */
    private $productsCollection = [];

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
     * Product data mapper
     *
     * @var \Application\Modules\Prom\Services\DataMappers\ProductMapper $productMapper
     */
    private $productMapper = null;


    /**
     * Init connection
     *
     * @param array $config
     */
    public function __construct(array $config) {
        $this->shopMapper       = new ShopMapper($config);
        $this->categoryMapper   = new CategoryMapper($config);
        $this->productMapper    = new ProductMapper($config);
    }

    /**
     * Format export data to output template
     *
     * @return array
     */
    public function loadExportData() {

        // Load Shop data
        $shop = $this->shopMapper->loadShop();
        var_dump('Load Shop data', $shop);

        $categories = $this->categoryMapper->loadCategories();
        var_dump('Load categories data', $categories);

        exit;

        $products = $this->productMapper->loadProducts();
        $productCategories = $this->productMapper->loadProductsCategories();

        $productsSplit = array_chunk($products, 50, true);

        foreach($productsSplit as $prod) {
            foreach($prod as $productId => $property) {

                $this->productsCollection[$productId] = (new ProductModel(
                    $productId,
                    $property['name'],
                    $property['available'],
                    $productCategories[$productId]['categoryId']
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