<?php
namespace Application\Modules\Amazon\Services\Checkout;

/**
 * Class CheckoutService
 *
 * @package Application\Modules\Amazon\Services\Checkout
 */
class CheckoutService {

    /**
     * API Config
     *
     * @var array Api
     */
    private $apiConfig = null;

    /**
     * Checkout data
     *
     * @var string $orderId
     */
    private $orderId = '';

    /**
     * Init
     *
     * @param array $orderId incoming customer data
     * @param array $apiConfig api config
     */
    public function __construct($orderId, array $apiConfig) {

        $this->orderId = $orderId;
        $this->apiConfig = $apiConfig;
    }

    /**
     * Get checkout Id
     *
     * @return string
     */
    public function getCheckoutId() {

        return hash('sha256', $this->orderId);
    }

}