<?php
namespace Application\Modules\Amazon\Services\Customer;

use Application\Exceptions\APIException;
use Application\Exceptions\BadResponseException;
use Application\Libraries\API;

/**
 * Class CustomerService
 *
 * @package Application\Modules\Amazon\Services
 */
class CustomerService {

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
     * Customer data
     *
     * @var array $customerData
     */
    private $customerData = [];

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
     * Api configuration
     *
     * @return $this
     */
    private function setApi() {

        $this->api = new API($this->apiConfig['token']);
        $this->api->setUrl($this->apiConfig['url'].$this->apiConfig['path']['customer']);
        $this->api->setMethod($this->api->isPost);

        return $this;
    }

    /**
     * Setup customer data
     *
     * @param array $customer
     * @return \Application\Modules\Amazon\Services\Customer\CustomerService
     */
    private function setCustomerInput(array $customer) {

        $this->customerData = $customer;
        $params = [
            'email' =>  $this->customerData['BuyerEmail'],
        ];

        $this->api->setParams($params);
        $this->api->setMethod($this->api->isGet);

        return $this;
    }

    /**
     * Get customer Id
     *
     * @throws BadResponseException
     * @return int
     */
    public function getCustomerId() {

        try {
            $result = $this->api->call();

            if(!isset($result['collection']) || empty($result['collection']) === true) {
                throw new BadResponseException('No collection for customer', BadResponseException::CODE);
            }
            if(isset($result['collection']['items'][0])) {
                return $result['collection']['items'][0]['data']['id'];
            }
            else if(empty($result['collection']['items']) === false) {
                return $result['collection']['items']['data']['id'];
            }

            return $this->createCustomer();
        }
        catch(APIException $e) {
            throw new BadResponseException($e->getMessage(), BadResponseException::CODE);
        }
    }

    /**
     * Create customer Id
     *
     * @throws BadResponseException
     * @return int
     */
    public function createCustomer() {

        $customerFio = explode(" ", $this->customerData['BuyerName']);
        $customerFio[1] = (!isset($customerFio[1])) ? $customerFio[0] : $customerFio[1];

        $params = [
            'firstName' =>  $customerFio[0],
            'lastName'  =>  $customerFio[1],
            'email'     =>  $this->customerData['BuyerEmail'],
            'siteId'    =>  $this->apiConfig['siteId'],
            'password'  =>  uniqid($this->apiConfig['siteId'].'_')
        ];

        try {

            $this->api->setParams($params);
            $this->api->setMethod($this->api->isPost);

            $result = $this->api->call();

            if(!isset($result['data']) || empty($result['data']) === true) {
                throw new BadResponseException('No response from create customer', BadResponseException::CODE);
            }

            return $result['data']['id'];
        }
        catch(APIException $e) {
            throw new BadResponseException($e->getMessage(), BadResponseException::CODE);
        }
    }

}