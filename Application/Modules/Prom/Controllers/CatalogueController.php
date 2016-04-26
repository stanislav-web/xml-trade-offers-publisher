<?php
namespace Application\Modules\Prom\Controllers;

use Application\Modules\Prom\Services\CatalogueExportService;

/**
 * Class CatalogueController
 *
 * @package Application\Modules\Prom\Controllers
 */
class CatalogueController extends ControllerBase {

    /**
     * Catalogue export service
     *
     * @var \Application\Modules\Prom\Services\CatalogueExportService $catalogueExportService
     */
    private $catalogueExportService;

    /**
     * Initialize services
     */
    public final function __construct() {

        parent::__construct();

        $this->catalogueExportService = new CatalogueExportService($this->partnerConfig);
    }
    /**
     * Export catalogue action
     */
    public function exportAction() {

        var_dump('Export data', $this->catalogueExportService->loadExportData());

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