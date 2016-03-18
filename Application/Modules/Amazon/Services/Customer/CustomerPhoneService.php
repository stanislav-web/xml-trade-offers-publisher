<?php
namespace Application\Modules\Amazon\Services\Customer;

use Application\Exceptions\APIException;
use Application\Exceptions\BadResponseException;
use Application\Exceptions\NotFoundException;
use Application\Libraries\API;

/**
 * Class CustomerPhoneService
 *
 * @package Application\Modules\Amazon\Services
 */
class CustomerPhoneService {

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
     * Customer phone data
     *
     * @var array $customerPhoneData
     */
    private $customerPhoneData = [];

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
     * @return \Application\Modules\Amazon\Services\Customer\CustomerPhoneService
     */
    private function setCustomerInput(array $customer) {

        $this->customerPhoneData = $customer;
        $params = [
            'customerId' =>  $this->customerPhoneData['customerId'],
            'phone' =>  $this->customerPhoneData['Phone']
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

        $this->api->setUrl($this->apiConfig['url'].$this->apiConfig['path']['customerPhones']);
        $this->api->setMethod($this->api->isGet);

        return $this;
    }

    /**
     * Get customer region Id
     *
     * @throws BadResponseException
     * @return int
     */
    public function getCustomerPhoneId() {

        try {
            $result = $this->api->call();

            if(!isset($result['collection']) || empty($result['collection']) === true) {
                throw new BadResponseException('No collection from load phones', BadResponseException::CODE);
            }

            if(isset($result['collection']['items'][0])) {
                return $result['collection']['items'][0]['data']['id'];
            }
            else if(empty($result['collection']['items']['data']) === false) {
                return $result['collection']['items']['data']['id'];
            }

            return $this->createCustomerPhone();
        }
        catch(APIException $e) {
            throw new BadResponseException($e->getMessage(), BadResponseException::CODE);
        }
    }

    /**
     * Create customer phone
     *
     * @throws BadResponseException
     * @return int
     */
    public function createCustomerPhone() {

        $params = [
            'customerId'   =>      $this->customerPhoneData['customerId'],
            'phone'      =>      $this->customerPhoneData['Phone'],
            'countryId' =>  	$this->customerPhoneData['countryId'],
        ];

        try {

            $this->api->setParams($params);
            $this->api->setMethod($this->api->isPost);

            $result = $this->api->call();

            if(!isset($result['data']) || empty($result['data']) === true) {
                throw new BadResponseException('Region does not created', BadResponseException::CODE);
            }

            return $result['data']['id'];
        }
        catch(APIException $e) {
            throw new BadResponseException($e->getMessage(), BadResponseException::CODE);
        }
    }

}