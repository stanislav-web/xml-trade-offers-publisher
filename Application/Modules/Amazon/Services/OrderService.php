<?php
namespace Application\Modules\Amazon\Services;

use Application\Exceptions\BadRequestException;
use Application\Exceptions\NotFoundException;
use Application\Modules\Amazon\Providers\ClientOrderProvider;
use Application\Exceptions\InternalServerErrorException;
use Application\Exceptions\ExportFactoryException;
use MarketplaceWebServiceOrders_Client as AmazonClient;
use Application\Modules\Amazon\Services\OrderCollectorService;

/**
 * Class OrderService
 *
 * @package Application\Modules\Amazon\Service
 * @link http://docs.developer.amazonservices.com/en_US/orders/2013-09-01/
 */
class OrderService {

    /**
     * Order's load path %s - date ISO
     *
     * @const SERVICE_LOAD_PATH
     */
    const SERVICE_LOAD_PATH = '/Orders/%s';

    /**
     * Configurations
     *
     * @var array $config
     */
    private $config;

    /**
     * Client order provider
     *
     * @var \Application\Aware\Providers\Client $ClientOrderProvider
     */
    private $clientOrderProvider;

    /**
     * Init service
     *
     * @param array $config
     */
    public function __construct(array $config) {
        $this->config = $config;

        $this->config['export']['config']['ServiceURL'] = $this->config['export']['config']['ServiceURL']
            .sprintf(self::SERVICE_LOAD_PATH, $this->config['export']['auth']["applicationVersion"]);
    }

    /**
     * Amazon Order loader
     *
     * @param string $date
     * @return \MarketplaceWebServiceOrders_Model_ListOrdersResponse|void
     * @throws InternalServerErrorException
     */
    public function loadOrders($date) {

        try {

            // load client
            $this->clientOrderProvider = (new ClientOrderProvider(
                (new AmazonClient($this->config['export']['auth']['awsAccessKeyId'],
                    $this->config['export']['auth']['awsSecretAccessKey'],
                    $this->config['export']['auth']['applicationName'],
                    $this->config['export']['auth']['applicationVersion'],
                    $this->config['export']['config']
                )), $this->config['export']['auth']
            ));

            $ordersCreateDate = $this->setOrderCreateDate($date);
            $ordersCollection = $this->clientOrderProvider->getOrders($ordersCreateDate, $this->config['export']['statuses']);

            $ordersCollectionItems = $this->clientOrderProvider->loadOrderItems($ordersCollection['orders']);

            // create order
            return new OrderCollectorService($ordersCollection, $ordersCollectionItems, $this->config['orderApi']);
        }
        catch(NotFoundException $e) {
            throw new NotFoundException($e->getMessage(), NotFoundException::CODE);
        }
        catch(BadRequestException $e) {
            throw new InternalServerErrorException($e->getMessage(), InternalServerErrorException::CODE);
        }
        catch(ExportFactoryException $e) {
            throw new InternalServerErrorException($e->getMessage(), InternalServerErrorException::CODE);
        }

    }

    /**
     * Set order create after date
     *
     * @param int $date
     * @return int
     */
    private function setOrderCreateDate($date) {
        $date = ($date != null)
            ? time() - $date : time() - $this->config['export']['config']['ordersCreatedAfter'];

        return gmdate('Y-m-d\TH:i:s\Z', $date);
    }
}