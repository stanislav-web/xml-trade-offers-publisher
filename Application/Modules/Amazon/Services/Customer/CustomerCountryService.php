<?php
namespace Application\Modules\Amazon\Services\Customer;

use Application\Exceptions\APIException;
use Application\Exceptions\BadResponseException;
use Application\Libraries\API;

/**
 * Class CustomerCountryService
 *
 * @package Application\Modules\Amazon\Services
 */
class CustomerCountryService {

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
     * Customer country data
     *
     * @var string $customerCountry
     */
    private $customerCountry = '';

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
        $this->api->setUrl($this->apiConfig['url'].$this->apiConfig['path']['customerCountries']);
        $this->api->setMethod($this->api->isGet);

        return $this;
    }

    /**
     * Setup customer data
     *
     * @param array $customer
     * @return \Application\Modules\Amazon\Services\Customer\CustomerCountryService
     */
    private function setCustomerInput(array $customer) {

        $this->customerCountry = $customer;
        $params = [
            'iso' =>  $this->customerCountry['CountryCode'],
        ];

        $this->api->setParams($params);

        return $this;
    }

    /**
     * Get customer country Id
     *
     * @throws BadResponseException
     * @return int
     */
    public function getCustomerCountryId() {

        try {
            $result = $this->api->call();

            if(!isset($result['collection']) || empty($result['collection']) === true) {
                throw new BadResponseException('No collection from load countries', BadResponseException::CODE);
            }

            if(isset($result['collection']['items'][0])) {
                return $result['collection']['items'][0]['data']['id'];
            }
            else if(empty($result['collection']['items']['data']) === false) {
                return $result['collection']['items']['data']['id'];
            }

            throw new BadResponseException('Country code `'.$this->customerCountryData['CountryCode'].'` does not found', BadResponseException::CODE);
        }
        catch(APIException $e) {
            throw new BadResponseException($e->getMessage(), BadResponseException::CODE);
        }
    }

}