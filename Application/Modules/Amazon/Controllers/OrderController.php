<?php
namespace Application\Modules\Amazon\Controllers;

use Application\Exceptions\InternalServerErrorException;
use Application\Exceptions\NotFoundException;
use Application\Modules\Amazon\Services\OrderService;

/**
 * Class CatalogueController
 *
 * @package Application\Modules\Amazon\Controllers
 */
class OrderController extends ControllerBase {

    /**
     * Order service
     *
     * @var \Application\Modules\Amazon\Services\OrderService $orderService
     */
    private $orderService;

    /**
     * Initialize services
     */
    public final function __construct() {

        parent::__construct();

        $this->orderService = new OrderService($this->partnerConfig);
    }
    /**
     * Export catalogue action
     */
    public function exportAction() {

        try {

            // load orders ( optional : date )
            $orders = $this->orderService->loadOrders($this->getQueryString()->getDate());

        }
        catch(NotFoundException $e) {
            throw new NotFoundException($e->getMessage(), NotFoundException::CODE);
        }
        catch(\Exception $e) {
            throw new InternalServerErrorException($e->getMessage(), InternalServerErrorException::CODE);
        }

        //@todo collect to view
        if($this->view->isCached() === false) {

            // save to cache
            $content = $this->view->output();
            return $this->view->cache($content);
        }
        
        return $this->view->output();
    }

}