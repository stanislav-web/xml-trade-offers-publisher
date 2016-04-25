<?php
namespace Application\Modules\Prom\Controllers;

use Application\Modules\Prom\Services\ProductExportService;

/**
 * Class CatalogueController
 *
 * @package Application\Modules\Prom\Controllers
 */
class CatalogueController extends ControllerBase {

    /**
     * Product export service
     *
     * @var \Application\Modules\Prom\Services\ProductExportService $productExportService
     */
    private $productExportService;

    /**
     * Initialize services
     */
    public final function __construct() {

        parent::__construct();

        $this->productExportService = new ProductExportService($this->partnerConfig);
    }
    /**
     * Export catalogue action
     */
    public function exportAction() {

        var_dump('Partner config', $this->partnerConfig);
        var_dump('View config', $this->view);
        var_dump('Export data', $this->productExportService->loadExportData());
        $prod = $this->productExportService->loadExportData();


        exit;
        if($this->view->isCached() === false) {

            $this->view->set('products', $prod);

            // save to cache
            $content = $this->view->output();
            return $this->view->cache($content);
        }

        return $this->view->output();
    }
}