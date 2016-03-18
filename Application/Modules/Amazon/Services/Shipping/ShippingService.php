<?php
namespace Application\Modules\Amazon\Services\Shipping;

use Application\Exceptions\APIException;
use Application\Exceptions\BadResponseException;
use Application\Libraries\API;

/**
 * Class ShippingService
 *
 * @package Application\Modules\Amazon\Services\Shipping
 */
class ShippingService {

    const AMAZON_SHIPPING_COST = 0;

    const AMAZON_SHIPPING_PERIOD = 5;

    /**
     * API
     *
     * @var \Application\Libraries\API Api
     */
    private $api = null;

    /**
     * API Config
     *
     * @var array Api
     */
    private $apiConfig = null;

    /**
     * Shipping data
     *
     * @var array $shippingData
     */
    private $shippingData = [];

    /**
     * Init
     *
     * @param array $customer incoming customer data
     * @param array $apiConfig api config
     */
    public function __construct(array $customer, array $apiConfig) {

        $this->apiConfig = $apiConfig;
        $this->setApi();
        $this->setCustomerInput($customer);
    }

    /**
     * Setup customer data
     *
     * @param array $customer
     * @return \Application\Modules\Amazon\Services\Shipping\ShippingService
     */
    private function setCustomerInput(array $customer) {

        $this->shippingData = $customer;
        $params = [
            'name' =>  $this->shippingData['ShipServiceLevel'],
        ];

        $this->api->setParams($params);

        return $this;
    }

    /**
     * Api configuration
     *
     * @return $this
     */
    private function setApi() {

        $this->api = new API($this->apiConfig['token']);

        $this->api->setUrl($this->apiConfig['url'].$this->apiConfig['path']['customerShipping']);
        $this->api->setMethod($this->api->isGet);

        return $this;
    }

    /**
     * Get shipping method Id
     *
     * @throws BadResponseException
     * @return int
     */
    public function getShippingMethodId() {

        try {
            $result = $this->api->call();

            if(!isset($result['collection']) || empty($result['collection']) === true) {
                throw new BadResponseException('No collection from load shipping methods', BadResponseException::CODE);
            }

            if(empty($result['collection']['items']['data']) === false) {
                return $result['collection']['items']['data']['id'];
            }
            else if(isset($result['collection']['items'][0])) {
                return $result['collection']['items'][0]['data']['id'];
            }

            return $this->createShippingMethod();
        }
        catch(APIException $e) {
            throw new BadResponseException($e->getMessage(), BadResponseException::CODE);
        }
    }

    /**
     * Create shipping method
     *
     * @throws BadResponseException
     * @return int
     */
    public function createShippingMethod() {

        $params = [
            'name'      =>  $this->shippingData['ShipServiceLevel'],
            'cost'      =>  self::AMAZON_SHIPPING_COST,
            'period'    =>  self::AMAZON_SHIPPING_PERIOD,
            'description'   => ''
        ];

        try {

            $this->api->setParams($params);
            $this->api->setMethod($this->api->isPost);

            $result = $this->api->call();

            if(!isset($result['data']) || empty($result['data']) === true) {
                throw new BadResponseException('Shipping method does not created', BadResponseException::CODE);
            }

            return $result['data']['id'];
        }
        catch(APIException $e) {
            throw new BadResponseException($e->getMessage(), BadResponseException::CODE);
        }
    }

}