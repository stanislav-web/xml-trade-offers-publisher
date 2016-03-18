<?php
namespace Application\Modules\Amazon\Controllers;

use Application\Modules\Amazon\Services\ProductService;

/**
 * Class CatalogueController
 *
 * @package Application\Modules\Amazon\Controllers
 */
class CatalogueController extends ControllerBase {

    /**
     * Product service
     *
     * @var \Application\Modules\Amazon\Services\ProductService $productService
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

            $this->view->set('test', 'test2222222');

            // save to cache
            $content = $this->view->output();
            return $this->view->cache($content);
        }

        return $this->view->output();
    }
}