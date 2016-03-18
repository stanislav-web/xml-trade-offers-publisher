<?php
namespace Application\Modules\Amazon\Services;

use Application\Modules\Amazon\Services\Checkout\CheckoutService;
use Application\Modules\Amazon\Services\Currency\CurrencyService;
use Application\Modules\Amazon\Services\Customer\CustomerPhoneService;
use Application\Modules\Amazon\Services\Customer\CustomerService;
use Application\Modules\Amazon\Services\Customer\CustomerAddressService;
use Application\Modules\Amazon\Services\Customer\CustomerRegionService;
use Application\Modules\Amazon\Services\Customer\CustomerCountryService;
use Application\Modules\Amazon\Services\Shipping\ShippingService;
use Application\Modules\Amazon\Services\Payment\PaymentService;
use Application\Modules\Amazon\Services\Warehouse\WarehouseService;

use Application\Models\OrderModel;
use Application\Models\OrderItemsModel;

/**
 * Class OrderCollectorService
 */
class OrderCollectorService {

    private $apiConfig = [];

    /**
     * Order model validator
     *
     * @var \Application\Models\OrderModel $orderModel
     */
    private $orderCollection = null;

    /**
     * Order items model validator
     *
     * @var \Application\Models\OrderItemsModel $orderModel
     */
    private $orderItemsCollection = null;


    public function __construct(array $orders, array $orderItems, array $config) {

        $this->apiConfig = $config;
        $this->setOrdersParams($orders['orders']);
    }

    /**
     * Setup orders params
     *
     * @param array $orders
     */
    private function setOrdersParams(array $orders) {

        foreach($orders as $orderId => $order) {

            $orderParams = [];

            // load customer id
            $order['Shipping']['customerId'] = (new CustomerService($order['Shipping'], $this->apiConfig))
                ->getCustomerId();
            $this->orderCollection[$orderId]['order']['customerId'] = $order['Shipping']['customerId'];

            // load customer country id
            $order['Shipping']['countryId'] = (new CustomerCountryService($order['Shipping'], $this->apiConfig))
                ->getCustomerCountryId();

            // load customer region id
            $order['Shipping']['regionId'] = (new CustomerRegionService($order['Shipping'], $this->apiConfig))
                ->getCustomerRegionId();

            // load customer phone
            $order['Shipping']['phoneId'] = (new CustomerPhoneService($order['Shipping'], $this->apiConfig))
                ->getCustomerPhoneId();

            // load order customer address
            $order['Shipping']['addressId'] = (new CustomerAddressService($order['Shipping'], $this->apiConfig))
                ->getCustomerAddressId();
            $this->orderCollection[$orderId]['order']['addressId'] = $order['Shipping']['addressId'];

            // load payment method
            $order['Shipping']['paymentMethodId'] = (isset($order['Payment'])) ? (new PaymentService($order['Payment'], $this->apiConfig))
                ->getPaymentMethodId() : PaymentService::DEFAULT_PAYMENT_ID;
            $this->orderCollection[$orderId]['order']['paymentMethodId'] = $order['Shipping']['paymentMethodId'];

            // load order shipping method
            $order['Shipping']['shippingMethodId'] = (new ShippingService($order['Shipping'], $this->apiConfig))
                ->getShippingMethodId();
            $this->orderCollection[$orderId]['order']['shippingMethodId'] = $order['Shipping']['shippingMethodId'];

            // load checkout id
            $order['Shipping']['checkoutId'] = (new CheckoutService($order['AmazonOrderId'], $this->apiConfig))->getCheckoutId();
            $this->orderCollection[$orderId]['order']['checkoutId'] = $order['Shipping']['checkoutId'];

            // load warehouse
            $order['Shipping']['warehouseId'] = (new WarehouseService($this->apiConfig))->getWarehouseId();
            $this->orderCollection[$orderId]['order']['warehouseId'] = $order['Shipping']['warehouseId'];

            // load currency id
            $order['Shipping']['currencyId'] = (new CurrencyService($order['OrderTotal']['CurrencyCode'], $this->apiConfig))->getCurrencyId();
            $this->orderCollection[$orderId]['order']['currencyId'] = $order['Shipping']['currencyId'];
        }

        print_r($this->orderCollection); exit;
    }
}