<?php
namespace Application\Modules\Prom\Controllers;

use Application\Modules\Prom\Services\ProductService;

/**
 * Class CatalogueController
 *
 * @package Application\Modules\Prom\Controllers
 */
class CatalogueController extends ControllerBase {

    /**
     * @var \Application\Modules\Prom\Services\ProductService $productService
     */
    private $productService;

    /**
     * Initialize services
     */
    public final function __construct() {

        parent::__construct();

        $this->productService = new ProductService($this->partnerConfig);
    }
    /**
     * Export catalogue action
     */
    public function exportAction() {

        exit('Export Prom');
        if($this->view->isCached() === false) {

            $this->view->set('test', 'test2222222');

            // save to cache
            $content = $this->view->output();
            return $this->view->cache($content);
        }

        return $this->view->output();
    }
}