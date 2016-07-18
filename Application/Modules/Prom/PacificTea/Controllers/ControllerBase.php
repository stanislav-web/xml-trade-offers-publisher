<?php
namespace Application\Modules\Prom\PacificTea\Controllers;

use Application\Services\Db;
use Application\Services\View;
use Application\Services\Request;

/**
 * Class ControllerBase
 *
 * @package Application\Controllers
 */
class ControllerBase {

    /**
     * Request handler
     *
     * @var \Application\Services\Request $request
     */
    private $request;

    /**
     * Application config
     *
     * @var array $config
     */
    protected $config;

    /**
     * Partner config
     *
     * @var array $partnerConfig
     */
    protected $partnerConfig;

    /**
     * View service
     *
     * @var \Application\Services\View $view
     */
    protected $view;

    /**
     * Init & filtering request data
     */
    public function __construct() {

        global $config;

        // set configuration
        $this->config = $config;
        // set request handler
        $this->request = new Request();

        // Partner config
        $this->partnerConfig = $this->config['services'][$this->request->getExportPartner()];

        // set view templater
        $this->view = new View(
            $this->partnerConfig,
            $this->request->getShop(),
            $this->request->getViewType()
        );
    }
}