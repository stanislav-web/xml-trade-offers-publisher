<?php
namespace Application\Modules\Amazon\Services\Payment;

use Application\Exceptions\APIException;
use Application\Exceptions\BadResponseException;
use Application\Libraries\API;

/**
 * Class PaymentService
 *
 * @package Application\Modules\Amazon\Services\Payment
 */
class PaymentService {

    /**
     * @const DEFAULT_PAYMENT_ID
     */
    const DEFAULT_PAYMENT_ID = 1;

    /**
     * @const AMAZON_PAYMENT_COMISSION
     */
    const AMAZON_PAYMENT_COMISSION = 0;

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
     * Payment data
     *
     * @var array $paymentData
     */
    private $paymentData = [];

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
     * @return \Application\Modules\Amazon\Services\Payment\PaymentService
     */
    private function setCustomerInput(array $customer) {

        $this->paymentData = $customer;
        $params = [
            'name' =>  $this->paymentData['PaymentMethod'],
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

        $this->api->setUrl($this->apiConfig['url'].$this->apiConfig['path']['customerPayment']);
        $this->api->setMethod($this->api->isGet);

        return $this;
    }

    /**
     * Get payment method Id
     *
     * @throws BadResponseException
     * @return int
     */
    public function getPaymentMethodId() {

        try {
            $result = $this->api->call();

            if(!isset($result['collection']) || empty($result['collection']) === true) {
                throw new BadResponseException('No collection from load payment methods', BadResponseException::CODE);
            }

            if(empty($result['collection']['items']['data']) === false) {
                return $result['collection']['items']['data']['id'];
            }
            else if(isset($result['collection']['items'][0])) {
                return $result['collection']['items'][0]['data']['id'];
            }

            return $this->createPaymentMethod();
        }
        catch(APIException $e) {
            throw new BadResponseException($e->getMessage(), BadResponseException::CODE);
        }
    }

    /**
     * Create payment method
     *
     * @throws BadResponseException
     * @return int
     */
    public function createPaymentMethod() {

        $params = [
            'name'      =>  $this->paymentData['PaymentMethod'],
            'paymentMethodType' =>  $this->paymentData['PaymentMethod'],
            'commission'        =>  self::AMAZON_PAYMENT_COMISSION,
            'description'       => ''
        ];

        try {

            $this->api->setParams($params);
            $this->api->setMethod($this->api->isPost);

            $result = $this->api->call();

            if(!isset($result['data']) || empty($result['data']) === true) {
                throw new BadResponseException('Payment method does not created', BadResponseException::CODE);
            }

            return $result['data']['id'];
        }
        catch(APIException $e) {
            throw new BadResponseException($e->getMessage(), BadResponseException::CODE);
        }
    }

}