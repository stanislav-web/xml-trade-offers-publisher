<?php
namespace Application\Modules\Prom\PacificTea\Services\DataMappers;

/**
 * @connect validate models
 */
use Application\Aware\Providers\Data;
use Application\Modules\Prom\PacificTea\Models\ProductCountryModel;
use Application\Modules\Prom\PacificTea\Models\ProductDescriptionModel;
use Application\Modules\Prom\PacificTea\Models\ProductKeywordsModel;
use Application\Modules\Prom\PacificTea\Models\ProductModel;
use Application\Modules\Prom\PacificTea\Models\ProductPhotosModel;
use Application\Modules\Prom\PacificTea\Models\ProductPriceModel;
use Application\Modules\Prom\PacificTea\Models\ProductPropertiesModel;

/**
 * Class ProductCollectionMapper
 *
 * @package Application\Modules\Prom\Service
 */
class ProductCollectionMapper {

    /**
     * Products separate parts for easy load
     *
     * @const PRODUCTS_CHUNK_MAP
     */
    const PRODUCTS_CHUNK_MAP = 40;

    /**
     * Ready products collection
     *
     * @var array $productsCollection
     */
    private $productsCollection = [];

    /**
     * Product data mapper
     *
     * @var \Application\Modules\Prom\PacificTea\Services\DataMappers\ProductMapper $productMapper
     */
    private $productMapper = null;


    /**
     * Init collection data mapper
     *
     * @param Data $productMapper
     */
    public function __construct(Data $productMapper) {

        $this->productMapper    = $productMapper;

        $this->addProducts();
        $this->addProductsPrices();
        $this->addProductsPhotos();
        $this->addProductsDescriptions();
        $this->addProductsProperties();
        $this->addProductsKeywords();
        $this->addProductsCountry();
    }

    /**
     * Load products
     *
     * @return array
     * @throws \Application\Exceptions\NotFoundException
     */
    public function load() {
        return $this->productsCollection;
    }

    /**
     * Add products to collection
     *
     * @throws \Application\Exceptions\NotFoundException
     */
    private function addProducts() {

        $products = $this->productMapper->loadProducts();
        $productsChunks = array_chunk($products, self::PRODUCTS_CHUNK_MAP, true);

        foreach($productsChunks as $products) {
            foreach($products as $productId => $product) {
                $this->productsCollection[$productId] = (new ProductModel(
                    $product['id'],
                    $product['articul'],
                    $product['name'],
                    $product['available'],
                    $product['categoryId']
                ))->toArray();
            }
        }
    }

    /**
     * Add prices to products collection
     *
     * @throws \Application\Exceptions\NotFoundException
     */
    private function addProductsPrices() {

        $productsChunks = array_chunk($this->productsCollection, self::PRODUCTS_CHUNK_MAP, true);

       foreach($productsChunks as $products) {
           $productsIds = array_keys($products);
           $productPrices = $this->productMapper->loadProductsPrices($productsIds);
           foreach($productPrices as $productId => $productPrice) {

               $this->productsCollection[$productId] = array_merge($this->productsCollection[$productId], (new ProductPriceModel(
                       $productPrice['productId'],
                       $productPrice['price'],
                       $productPrice['percent'],
                       $productPrice['discount'],
                       $productPrice['currencyId'],
                       $productPrice['currencyName']
                   ))->toArray());
            }
        }
    }

    /**
     * Add photos to products collection
     *
     * @throws \Application\Exceptions\NotFoundException
     */
    private function addProductsPhotos() {
        $productsChunks = array_chunk($this->productsCollection, self::PRODUCTS_CHUNK_MAP, true);

        foreach($productsChunks as $products) {
            $productsIds = array_keys($products);
            $productPhotos = $this->productMapper->loadProductsPhotos($productsIds);
            foreach($productPhotos as $productId => $productPhoto) {

                $this->productsCollection[$productId] = array_merge($this->productsCollection[$productId], (new ProductPhotosModel(
                    $productPhoto['productId'],
                    $this->productMapper->loadConfig()->params['shop']['imgPaths'],
                    $productPhoto['photos']
                ))->toArray());
            }
        }
    }

    /**
     * Add description to products collection
     *
     * @throws \Application\Exceptions\NotFoundException
     */
    private function addProductsDescriptions() {
        $productsChunks = array_chunk($this->productsCollection, self::PRODUCTS_CHUNK_MAP, true);

        foreach($productsChunks as $products) {
            $productsIds = array_keys($products);
            $productDescs = $this->productMapper->loadProductsDescription($productsIds);
            foreach($productDescs as $productId => $productDesc) {

                $this->productsCollection[$productId] = array_merge($this->productsCollection[$productId], (new ProductDescriptionModel(
                    $productDesc['productId'],
                    $productDesc['description']
                ))->toArray());
            }
        }
    }

    /**
     * Add properties to existing products
     */
    private function addProductsProperties() {

        $productsChunks = array_chunk($this->productsCollection, self::PRODUCTS_CHUNK_MAP, true);

        foreach($productsChunks as $products) {
            $productsIds = array_keys($products);
            $productProps = $this->productMapper->loadProductsProperties($productsIds);

            foreach($productProps as $productProp) {

                $this->productsCollection[$productProp['productId']]['properties'][] = (new ProductPropertiesModel(
                    $productProp['productId'],
                    $productProp['attributeId'],
                    $productProp['variantId'],
                    $productProp['name'],
                    $productProp['value']
                ))->toArray();
            }
        }
    }

    /**
     * Add keywords to products collection
     *
     * @throws \Application\Exceptions\NotFoundException
     */
    private function addProductsKeywords() {
        $productsChunks = array_chunk($this->productsCollection, self::PRODUCTS_CHUNK_MAP, true);

        foreach($productsChunks as $products) {
            $productsIds = array_keys($products);
            $productKeywords = $this->productMapper->loadProductsKeywords($productsIds);

            foreach($productKeywords as $productId => $productKeyword) {

                $this->productsCollection[$productId] = array_merge($this->productsCollection[$productId], (new ProductKeywordsModel(
                    $productKeyword['productId'],
                    $productKeyword['keyword']
                ))->toArray());
            }
        }
    }

    /**
     * Add country to products collection
     *
     * @throws \Application\Exceptions\NotFoundException
     */
    private function addProductsCountry() {
        $productsChunks = array_chunk($this->productsCollection, self::PRODUCTS_CHUNK_MAP, true);

        foreach($productsChunks as $products) {
            $productsIds = array_keys($products);
            $productCountries = $this->productMapper->loadProductsCountry($productsIds);
            foreach($productCountries as $productId => $productCountry) {

                $this->productsCollection[$productId] = array_merge($this->productsCollection[$productId], (new ProductCountryModel(
                    $productCountry['productId'],
                    $productCountry['country']
                ))->toArray());
            }
        }
    }

}