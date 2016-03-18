<?php
namespace Application\Modules\Amazon\Services\Customer;

use Application\Exceptions\APIException;
use Application\Exceptions\BadResponseException;
use Application\Libraries\API;

/**
 * Class CustomerRegionService
 *
 * @package Application\Modules\Amazon\Services
 */
class CustomerRegionService {

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
     * Customer region data
     *
     * @var array $customerCountryData
     */
    private $customerRegionData = [];

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
     * @return \Application\Modules\Amazon\Services\Customer\CustomerRegionService
     */
    private function setCustomerInput(array $customer) {

        $this->customerRegionData = $customer;
        $params = ['countryId' =>  $this->customerRegionData['countryId']];

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

        $this->api->setUrl($this->apiConfig['url'].$this->apiConfig['path']['customerRegions']);
        $this->api->setMethod($this->api->isGet);

        return $this;
    }

    /**
     * Get customer region Id
     *
     * @throws BadResponseException
     * @return int
     */
    public function getCustomerRegionId() {

        try {
            $result = $this->api->call();

            if(!isset($result['collection']) || empty($result['collection']) === true) {
                throw new BadResponseException('No collection from load countries', BadResponseException::CODE);
            }

            foreach($result['collection']['items'] as $region) {

                if(strtolower($region['data']['isoCode']) == strtolower($this->customerRegionData['StateOrRegion'])) {
                    return (int)$region['data']['id'];
                }

                if(strtolower($region['data']['name']) == strtolower($this->customerRegionData['StateOrRegion'])) {
                    return (int)$region['data']['id'];
                }
            }

            return $this->createCustomerRegion();
        }
        catch(APIException $e) {
            throw new BadResponseException($e->getMessage(), BadResponseException::CODE);
        }
    }

    /**
     * Create customer region
     *
     * @throws BadResponseException
     * @return int
     */
    public function createCustomerRegion() {

        $params = [
            'countryId' =>  	$this->customerRegionData['countryId'],
            'isoCode'   =>      $this->getIsoCodeRegion(),
            'name'      =>      $this->customerRegionData['StateOrRegion'],
            'phoneCode' =>      0,
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

    /**
     * Get region iso code from string
     *
     * @return string
     */
    private function getIsoCodeRegion() {

        $isoRegionCode = '';

        if(strlen($this->customerRegionData['StateOrRegion']) > 2) {
            $code = explode(' ', $this->customerRegionData['StateOrRegion']);
            foreach($code as $c) {
                $isoRegionCode .= $c[0];
            }
        }
        else {
            $isoRegionCode = $this->customerRegionData['StateOrRegion'];
        }

        return strtoupper(trim($isoRegionCode));
    }

}