<?php
namespace Application\Modules\Prom\Nothingshop\Controllers;

use Application\Modules\Prom\Nothingshop\Services\CatalogueExportService;

/**
 * Class CatalogueController
 *
 * @package Application\Modules\Prom\Nothingshop\Controllers
 */
class CatalogueController extends ControllerBase {

    /**
     * Catalogue export service
     *
     * @var \Application\Modules\Prom\Nothingshop\Services\CatalogueExportService $catalogueExportService
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