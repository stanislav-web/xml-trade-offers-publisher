<?php
namespace Application\Modules\Amazon\Services\Customer;

use Application\Exceptions\APIException;
use Application\Exceptions\BadResponseException;
use Application\Libraries\API;

/**
 * Class CustomerAddressService
 *
 * @package Application\Modules\Amazon\Services
 */
class CustomerAddressService {

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
     * Customer address data
     *
     * @var array $customerAddressData
     */
    private $customerAddressData = [];

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
        $this->api->setUrl($this->apiConfig['url'].$this->apiConfig['path']['customerAddress']);
        $this->api->setMethod($this->api->isGet);

        return $this;
    }

    /**
     * Setup customer data
     *
     * @param array $customer
     * @return \Application\Modules\Amazon\Services\Customer\CustomerAddressService
     */
    private function setCustomerInput(array $customer) {

        $this->customerAddressData = $customer;

        $params = [
            'customerId'    =>  $this->customerAddressData['customerId'],
            'countryId'     =>  $this->customerAddressData['countryId'],
            'regionId'      =>  $this->customerAddressData['regionId'],
            'address'       =>  $this->customerAddressData['AddressLine1'],
        ];

        $this->api->setParams($params);

        return $this;
    }

    /**
     * Create customer address
     *
     * @throws BadResponseException
     * @return int
     */
    public function getCustomerAddressId() {

        try {
            $result = $this->api->call();

            if(!isset($result['collection']) || empty($result['collection']) === true) {
                throw new BadResponseException('No collection from load addresses', BadResponseException::CODE);
            }

            if(empty($result['collection']['items']['data']) === false) {
                return $result['collection']['items']['data']['id'];
            }
            else if(isset($result['collection']['items'][0])) {
                return $result['collection']['items'][0]['data']['id'];
            }

            return $this->createCustomerAddress();
        }
        catch(APIException $e) {
            throw new BadResponseException($e->getMessage(), BadResponseException::CODE);
        }
    }

    /**
     * Create customer address
     *
     * @throws BadResponseException
     * @return int
     */
    public function createCustomerAddress() {

        $params = [
            'customerId'    =>  $this->customerAddressData['customerId'],
            'postalCode'    =>  $this->customerAddressData['PostalCode'],
            'countryId'     =>  $this->customerAddressData['countryId'],
            'regionId'      =>  $this->customerAddressData['regionId'],
            'city'          =>  $this->customerAddressData['City'],
            'address'       =>  $this->customerAddressData['AddressLine1'],
            'phoneId'       =>  $this->customerAddressData['phoneId'],
            'apartment'     =>  $this->customerAddressData['AddressLine1']
        ];

        try {

            $this->api->setParams($params);
            $this->api->setMethod($this->api->isPost);

            $result = $this->api->call();

            if(!isset($result['data']) || empty($result['data']) === true) {
                throw new BadResponseException('Address does not created', BadResponseException::CODE);
            }

            return $result['data']['id'];
        }
        catch(APIException $e) {
            throw new BadResponseException($e->getMessage(), BadResponseException::CODE);
        }
    }


}