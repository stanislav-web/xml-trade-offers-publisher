<?php
namespace Application\Modules\Prom\Lecabaret\Services\DataMappers;

/**
 * @connect validate models
 */
use Application\Aware\Providers\Data;
use Application\Modules\Prom\Lecabaret\Models\ProductCountryModel;
use Application\Modules\Prom\Lecabaret\Models\ProductDescriptionModel;
use Application\Modules\Prom\Lecabaret\Models\ProductKeywordsModel;
use Application\Modules\Prom\Lecabaret\Models\ProductModel;
use Application\Modules\Prom\Lecabaret\Models\ProductPhotosModel;
use Application\Modules\Prom\Lecabaret\Models\ProductPriceModel;
use Application\Modules\Prom\Lecabaret\Models\ProductPropertiesModel;
use Application\Modules\Prom\Lecabaret\Models\ProductBrandModel;
use Application\Modules\Prom\Lecabaret\Models\ProductSizesModel;

/**
 * Class ProductCollectionMapper
 *
 * @package Application\Modules\Prom\Lecabaret\Services\DataMappers
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
     * @var \Application\Modules\Prom\Lecabaret\Services\DataMappers\ProductMapper $productMapper
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
        $this->addProductsBrands();
        $this->addProductsKeywords();
        $this->addProductsCountry();
        $this->addProductsSizes();
        $this->addProductsProperties();
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
     * Add brands to products collection
     *
     * @throws \Application\Exceptions\NotFoundException
     */
    private function addProductsBrands() {
        $productsChunks = array_chunk($this->productsCollection, self::PRODUCTS_CHUNK_MAP, true);

        foreach($productsChunks as $products) {
            $productsIds = array_keys($products);
            $productBrands = $this->productMapper->loadProductsBrands($productsIds);

            foreach($productBrands as $productId => $productBrand) {

                $this->productsCollection[$productId] = array_merge($this->productsCollection[$productId], (new ProductBrandModel(
                    $productBrand['productId'],
                    $productBrand['brand']
                ))->toArray());
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

    /**
     * Add sizes to existing products
     */
    private function addProductsSizes() {

        $productsChunks = array_chunk($this->productsCollection, self::PRODUCTS_CHUNK_MAP, true);

        foreach($productsChunks as $products) {
            $productsIds = array_keys($products);
            $productSizes = $this->productMapper->loadProductsSizes($productsIds);

            foreach($productSizes as $productSize) {

                $this->productsCollection[$productSize['productId']]['sizes'][$productSize['variantId']] = (new ProductSizesModel(
                    $productSize['productId'],
                    $productSize['variantId'],
                    $productSize['size'],
                    $productSize['count']
                ))->toArray();
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

                if(isset($this->productsCollection[$productProp['productId']]['sizes'][$productProp['variantId']])) {
                    $this->productsCollection[$productProp['productId']]['sizes'][$productProp['variantId']]['properties'][] = (new ProductPropertiesModel(
                        $productProp['productId'],
                        $productProp['attributeId'],
                        $productProp['variantId'],
                        $productProp['name'],
                        $productProp['value'],
                        $productProp['unit']
                    ))->toArray();
                }
            }
        }
    }

}