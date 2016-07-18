<?php
namespace Application\Modules\Prom\PacificTea\Controllers;

use Application\Modules\Prom\PacificTea\Services\CatalogueExportService;

/**
 * Class CatalogueController
 *
 * @package Application\Modules\Prom\Controllers
 */
class CatalogueController extends ControllerBase {

    /**
     * Catalogue export service
     *
     * @var \Application\Modules\Prom\PacificTea\Services\CatalogueExportService $catalogueExportService
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

        $exportData = $this->catalogueExportService->exportData();

        if($this->view->isCached() === false) {

            $this->view->set('data', $exportData);

            // save to cache
            $content = $this->view->output();
            return $this->view->cache($content);
        }

        return $this->view->output();
    }
}