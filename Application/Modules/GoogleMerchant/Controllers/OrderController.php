<?php
namespace Application\Modules\GoogleMerchant\Controllers;

use Application\Modules\GoogleMerchant\Services\ProductService;

/**
 * Class CatalogueController
 *
 * @package Application\Modules\GoogleMerchant\Controllers
 */
class OrderController extends ControllerBase {

    /**
     * @var \Application\Modules\GoogleMerchant\Services\ProductService $productService
     */
    private $productService;

    /**
     * Initialize services
     */
    public final function __construct() {

        parent::__construct();

        $this->productService = new ProductService();
    }
    /**
     * Export catalogue action
     */
    public function exportAction() {

        if($this->view->isCached() === false) {

            $this->view->set('test', 'test2222');

            // save to cache
            $content = $this->view->output();
            return $this->view->cache($content);
        }
        
        return $this->view->output();
    }
}