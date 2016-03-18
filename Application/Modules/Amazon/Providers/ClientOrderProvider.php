<?php
namespace Application\Modules\Amazon\Providers;
use Application\Aware\Providers\Client;
use Application\Exceptions\ClientProviderException;
use Application\Exceptions\InternalServerErrorException;
use Application\Exceptions\NotFoundException;
use MarketplaceWebServiceOrders_Model_ListOrdersRequest as ListOrderRequest;
use MarketplaceWebServiceOrders_Model_ListOrderItemsRequest as ListOrderItemsRequest;

/**
 * Class ClientOrderProvider
 *
 * @package Application\Modules\Amazon\Providers
 * @link https://github.com/plugmystore/amazon-mws-orders
 * @link https://images-na.ssl-images-amazon.com/images/G/01/mwsportal/doc/en_US/orders/2013-09-01/MWSOrdersApiReference._V361505966_.pdf
 **/
class ClientOrderProvider extends Client {

    /**
     * Amazon merchant Id
     *
     * @var int $merchantId
     */
    protected $merchantId;

    /**
     * Amazon Marketplace Id
     *
     * @var int $marketplaceId
     */
    protected $marketplaceId;

    /**
     * MarketplaceWebService order client
     *
     * @var \MarketplaceWebServiceOrders_Interface $client
     */
    protected $orderClient;

    /**
     * Amazon request object
     *
     * @var ListOrderRequest $request
     */
    protected $request;

    /**
     * Amazon merchant config
     *
     * @var array $merchantConfig
     */
    protected $merchantConfig;

    /**
     * Init client
     *
     * @param \MarketplaceWebServiceOrders_Interface $orderClient
     * @param array $merchantConfig
     * @throws ClientProviderException
     */
    public function __construct($orderClient, array $merchantConfig) {

        parent::__construct($orderClient, $merchantConfig, '_config');

        if(is_object($orderClient) === false) {
            throw new ClientProviderException('Object error implementation', ClientProviderException::CODE);
        }

        // set client & merchant config
        $this->orderClient = $orderClient;
        $this->merchantConfig = $merchantConfig;
    }

    /**
     * List all orders updated after a certain date
     *
     * @param string $date
     * @param array  $orderStatuses
     * @throws InternalServerErrorException
     * @throws NotFoundException
     * @link http://docs.developer.amazonservices.com/en_US/orders/2013-09-01/Orders_ListOrders.html
     * @link https://mws.amazonservices.ca/Orders/%s?Action=listOrders&SellerId=1&CreatedAfter=2016-02-23T14%3A06%3A41%2B0000&OrderStatus.Status.1=Pending&MarketplaceId.Id.1=1&AWSAccessKeyId=12124654645746765756&Timestamp=2016-02-23T14%3A06%3A41.000Z&Version=2013-09-01&SignatureVersion=2&SignatureMethod=HmacSHA256&Signature=g4xOYeHF5f9oIH8RPv5DIyw0QklnZmVDv10cKG3KdP8%3D
     *
     * @return array
     */
    public function getOrders($date, array $orderStatuses) {

        try {

            // ini list order request
            $this->request = new ListOrderRequest();

            // set seller id
            $this->request->setSellerId($this->merchantConfig['sellerId']);

            // set marketplace id
            $this->request->setMarketplaceId($this->merchantConfig['marketplaceId']);

            // set create order's date
            $this->request->setCreatedAfter($date);

            // set the order statuses
            $this->request->setOrderStatus($orderStatuses);

            // create request to listOrders
            $response = $this->orderClient->listOrders($this->request);

            // process Amazon ListOrders response
            $orders = $this->processListOrdersResponse($response);

            return $orders;
        }
        catch(\MarketplaceWebServiceOrders_Exception $e) {

            $message = $e->getErrorMessage();

            if(empty($message) === true) {
                throw new NotFoundException("Orders does not found", NotFoundException::CODE);
            };

            throw new InternalServerErrorException($message, InternalServerErrorException::CODE);
        }
    }

    /**
     * List of all order's items
     *
     * @param array $orderIds
     * @return mixed
     */
    public function loadOrderItems(array $orderIds) {

        try {
            $orderItems = [];
            $orderIds = array_keys($orderIds);

            // ini list order items request
            $this->request = new ListOrderItemsRequest();

            // set seller id
            $this->request->setSellerId($this->merchantConfig['sellerId']);

            if(empty($orderIds) === true) {
                throw new NotFoundException("Orders items does not found in loadOrderItems", NotFoundException::CODE);
            }

            foreach($orderIds as $orderId) {

                // set Amazon order id
                $this->request->setAmazonOrderId($orderId);

                // create request to listOrderItems
                $response = $this->orderClient->listOrderItems($this->request);

                // process Amazon listOrdersItems response
                $orderItems[$orderId] = $this->processListOrderItemsResponse($response);
            }

            return $orderItems;
        }
        catch(\MarketplaceWebServiceOrders_Exception $e) {

            $message = $e->getErrorMessage();

            if(empty($message) === true) {
                throw new NotFoundException("Orders items does not found", NotFoundException::CODE);
            };

            throw new InternalServerErrorException($message, InternalServerErrorException::CODE);
        }
    }

    /**
     * Process fetched orders by request
     *
     * @param \MarketplaceWebServiceOrders_Model_ListOrdersResponse $response
     * @return array
     */
    private function processListOrdersResponse(\MarketplaceWebServiceOrders_Model_ListOrdersResponse $response) {

        $listOrdersCollection = [];

        if($response->isSetListOrdersResult()) {

            $listOrdersResult = $response->getListOrdersResult();
            if($listOrdersResult->isSetNextToken()) {
                $listOrdersCollection['NextToken'] = $listOrdersResult->getNextToken();
            }

            if($listOrdersResult->isSetCreatedBefore()) {
                $listOrdersCollection['CreatedBefore'] = $listOrdersResult->getCreatedBefore();
            }
            if($listOrdersResult->isSetLastUpdatedBefore()) {
                $listOrdersCollection['LastUpdatedBefore'] = $listOrdersResult->getLastUpdatedBefore();
            }

            if($listOrdersResult->isSetOrders()) {

                $orderList = $listOrdersResult->getOrders();
                foreach($orderList as $order) {

                    if($order->isSetAmazonOrderId()) {
                        $listOrdersCollection['orders'][$order->getAmazonOrderId()]['AmazonOrderId']
                            = $order->getAmazonOrderId();
                    }
                    if($order->isSetMarketplaceId()) {
                        $listOrdersCollection['orders'][$order->getAmazonOrderId()]['MarketplaceId']
                            = $order->getMarketplaceId();
                    }

                    if($order->isSetSellerOrderId()) {
                        $listOrdersCollection['orders'][$order->getAmazonOrderId()]['SellerOrderId']
                            = $order->getSellerOrderId();
                    }

                    if($order->isSetPurchaseDate()) {
                        $listOrdersCollection['orders'][$order->getAmazonOrderId()]['PurchaseDate']
                            = $order->getPurchaseDate();
                    }
                    if($order->isSetLastUpdateDate()) {
                        $listOrdersCollection['orders'][$order->getAmazonOrderId()]['LastUpdateDate']
                            = $order->getLastUpdateDate();
                    }
                    if($order->isSetOrderStatus()) {

                        $listOrdersCollection['orders'][$order->getAmazonOrderId()]['OrderStatus']
                            = $order->getOrderStatus();
                    }
                    if($order->isSetFulfillmentChannel()) {
                        $listOrdersCollection['orders'][$order->getAmazonOrderId()]['FulfillmentChannel']
                            = $order->getFulfillmentChannel();
                    }
                    if($order->isSetSalesChannel()) {

                        $listOrdersCollection['orders'][$order->getAmazonOrderId()]['SalesChannel']
                            = $order->getSalesChannel();
                    }
                    if ($order->isSetOrderChannel())
                    {
                        $listOrdersCollection['orders'][$order->getAmazonOrderId()]['OrderChannel']
                            = $order->getOrderChannel();
                    }
                    if ($order->isSetShipServiceLevel())
                    {
                        $listOrdersCollection['orders'][$order->getAmazonOrderId()]['ShipServiceLevel']
                            = $order->getShipServiceLevel();
                    }

                    // SHIPPING

                    if ($order->isSetShippingAddress()) {

                        $listOrdersCollection['orders'][$order->getAmazonOrderId()]['Shipping']['ShipServiceLevel']
                            = $order->getShipServiceLevel();

                        $shippingAddress = $order->getShippingAddress();

                        if($shippingAddress->isSetName()) {
                            $listOrdersCollection['orders'][$order->getAmazonOrderId()]['Shipping']['Name']
                                = $shippingAddress->getName();
                        }
                        if($shippingAddress->isSetAddressLine1()) {

                            $listOrdersCollection['orders'][$order->getAmazonOrderId()]['Shipping']['AddressLine1']
                                = $shippingAddress->getAddressLine1();
                        }
                        if($shippingAddress->isSetAddressLine2()) {

                            $listOrdersCollection['orders'][$order->getAmazonOrderId()]['Shipping']['getAddressLine2']
                                = $shippingAddress->getAddressLine2();
                        }
                        if($shippingAddress->isSetAddressLine3()) {
                            $listOrdersCollection['orders'][$order->getAmazonOrderId()]['Shipping']['getAddressLine3']
                                = $shippingAddress->getAddressLine3();
                        }
                        if($shippingAddress->isSetCity()) {

                            $listOrdersCollection['orders'][$order->getAmazonOrderId()]['Shipping']['City']
                                = $shippingAddress->getCity();
                        }
                        if ($shippingAddress->isSetCounty())
                        {
                            $listOrdersCollection['orders'][$order->getAmazonOrderId()]['Shipping']['County']
                                = $shippingAddress->getCounty();
                        }
                        if($shippingAddress->isSetDistrict()) {
                            $listOrdersCollection['orders'][$order->getAmazonOrderId()]['Shipping']['District']
                                = $shippingAddress->getDistrict();
                        }
                        if($shippingAddress->isSetStateOrRegion()) {
                            $listOrdersCollection['orders'][$order->getAmazonOrderId()]['Shipping']['StateOrRegion']
                                = $shippingAddress->getStateOrRegion();
                        }
                        if($shippingAddress->isSetPostalCode()) {
                            $listOrdersCollection['orders'][$order->getAmazonOrderId()]['Shipping']['PostalCode']
                                = $shippingAddress->getPostalCode();
                        }
                        if($shippingAddress->isSetCountryCode()) {
                            $listOrdersCollection['orders'][$order->getAmazonOrderId()]['Shipping']['CountryCode']
                                = $shippingAddress->getCountryCode();
                        }
                        if($shippingAddress->isSetPhone())
                        {
                            $listOrdersCollection['orders'][$order->getAmazonOrderId()]['Shipping']['Phone']
                                = $shippingAddress->getPhone();
                        }
                        if($shippingAddress->isSetPhone())
                        {
                            $listOrdersCollection['orders'][$order->getAmazonOrderId()]['Shipping']['Phone']
                                = $shippingAddress->getPhone();
                        }
                        if($order->isSetBuyerName())  {
                            $listOrdersCollection['orders'][$order->getAmazonOrderId()]['Shipping']['BuyerName']
                                = $order->getBuyerName();
                        }

                        if($order->isSetBuyerEmail())  {
                            $listOrdersCollection['orders'][$order->getAmazonOrderId()]['Shipping']['BuyerEmail']
                                = $order->getBuyerEmail();
                        }

                        if($order->isSetShipmentServiceLevelCategory())  {
                            $listOrdersCollection['orders'][$order->getAmazonOrderId()]['Shipping']['Category']
                                = $order->getShipmentServiceLevelCategory();
                        }

                        // TOTAL
                        if ($order->isSetOrderTotal()) {

                            $orderTotal = $order->getOrderTotal();

                            if($orderTotal->isSetCurrencyCode()) {
                                $listOrdersCollection['orders'][$order->getAmazonOrderId()]['OrderTotal']['CurrencyCode']
                                    = $orderTotal->getCurrencyCode();
                            }
                            if($orderTotal->isSetAmount()) {
                                $listOrdersCollection['orders'][$order->getAmazonOrderId()]['OrderTotal']['Amount']
                                    = $orderTotal->getAmount();
                            }

                            if($order->isSetNumberOfItemsShipped()) {

                                $listOrdersCollection['orders'][$order->getAmazonOrderId()]['OrderTotal']['NumberOfItemsShipped']
                                    = $order->getNumberOfItemsShipped();
                            }
                            if($order->isSetNumberOfItemsUnshipped()) {
                                $listOrdersCollection['orders'][$order->getAmazonOrderId()]['OrderTotal']['NumberOfItemsUnshipped']
                                    = $order->getNumberOfItemsUnshipped();
                            }

                            // PAYMENT
                            if ($order->isSetPaymentExecutionDetail()) {

                                $paymentExecutionDetail = $order->getPaymentExecutionDetail();

                                foreach ($paymentExecutionDetail as $paymentExecutionDetailItem) {
                                    if ($paymentExecutionDetailItem->isSetPayment()) {

                                        $payment = $paymentExecutionDetailItem->getPayment();
                                        if($payment->isSetCurrencyCode()) {
                                            $listOrdersCollection['orders'][$order->getAmazonOrderId()]['Payment']['CurrencyCode']
                                                = $payment->getCurrencyCode();
                                        }
                                        if($payment->isSetAmount()) {
                                            $listOrdersCollection['orders'][$order->getAmazonOrderId()]['Payment']['Amount']
                                                = $payment->getAmount();
                                        }
                                    }
                                    if($paymentExecutionDetailItem->isSetPaymentMethod()) {
                                        $listOrdersCollection['orders'][$order->getAmazonOrderId()]['Payment']['PaymentMethod']
                                            = $paymentExecutionDetailItem->getPaymentMethod();
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // DEBUG. Print metadata
            if($this->debug === true) {
                if ($response->isSetResponseMetadata()) {
                    $responseMetadata = $response->getResponseMetadata();
                    if($responseMetadata->isSetRequestId())  {
                        $listOrdersCollection['debug']['RequestId'] = $responseMetadata->getRequestId();
                    }
                }
                $listOrdersCollection['debug']['ResponseHeaderMetadata'] = $response->getResponseHeaderMetadata();
            }
        }

        return $listOrdersCollection;
    }

    /**
     * Process fetched order items by request
     *
     * @param \MarketplaceWebServiceOrders_Model_ListOrderItemsResponse $response
     * @return array
     */
    private function processListOrderItemsResponse(\MarketplaceWebServiceOrders_Model_ListOrderItemsResponse $response) {

        $listOrderItemsCollection = [];

        if($response->isSetListOrderItemsResult()) {

            $listOrderItemsResult = $response->getListOrderItemsResult();
            if($listOrderItemsResult->isSetNextToken()) {
                $listOrderItemsCollection['NextToken'] = $listOrderItemsResult->getNextToken();
            }

            if($listOrderItemsResult->isSetAmazonOrderId()) {
                $listOrderItemsCollection['AmazonOrderId'] = $listOrderItemsResult->getAmazonOrderId();
            }

            if($listOrderItemsResult->isSetOrderItems()) {

                $orderItems = $listOrderItemsResult->getOrderItems();

                foreach ($orderItems as $orderItem) {

                    if($orderItem->isSetASIN()) {
                        $listOrderItemsCollection['items'][$orderItem->getASIN()]['ASIN'] = $orderItem->getASIN();
                    }

                    if($orderItem->isSetSellerSKU()) {
                        $listOrderItemsCollection['items'][$orderItem->getASIN()]['SellerSKU'] = $orderItem->getSellerSKU();
                    }

                    if($orderItem->isSetSellerSKU()) {
                        $listOrderItemsCollection['items'][$orderItem->getASIN()]['SellerSKU'] = $orderItem->getSellerSKU();
                    }

                    if($orderItem->isSetOrderItemId()) {
                        $listOrderItemsCollection['items'][$orderItem->getASIN()]['OrderItemId'] = $orderItem->getOrderItemId();
                    }

                    if($orderItem->isSetTitle()) {
                        $listOrderItemsCollection['items'][$orderItem->getASIN()]['Title'] = $orderItem->getTitle();
                    }

                    if($orderItem->isSetQuantityOrdered()) {
                        $listOrderItemsCollection['items'][$orderItem->getASIN()]['QuantityOrdered'] = $orderItem->getQuantityOrdered();
                    }

                    if($orderItem->isSetQuantityShipped()) {
                        $listOrderItemsCollection['items'][$orderItem->getASIN()]['QuantityShipped'] = $orderItem->getQuantityShipped();
                    }


                    if($orderItem->isSetInvoiceData()) {
                        $invoiceData = $orderItem->getInvoiceData();

                        if($invoiceData->isSetInvoiceRequirement()) {
                            $listOrderItemsCollection['items'][$orderItem->getASIN()]['Invoice']['InvoiceRequirement']
                                = $invoiceData->getInvoiceRequirement();
                        }
                        if($invoiceData->isSetBuyerSelectedInvoiceCategory()) {
                            $listOrderItemsCollection['items'][$orderItem->getASIN()]['Invoice']['BuyerSelectedInvoiceCategory']
                                = $invoiceData->getBuyerSelectedInvoiceCategory();
                        }
                        if($invoiceData->isSetInvoiceTitle()) {

                            $listOrderItemsCollection['items'][$orderItem->getASIN()]['Invoice']['InvoiceTitle']
                                = $invoiceData->getInvoiceTitle();
                        }
                        if($invoiceData->isSetInvoiceInformation()) {

                            $listOrderItemsCollection['items'][$orderItem->getASIN()]['Invoice']['InvoiceInformation']
                                = $invoiceData->getInvoiceInformation();
                        }
                    }
                }
            }

            // DEBUG. Print metadata
            if($this->debug === true) {
                if ($response->isSetResponseMetadata()) {
                    $responseMetadata = $response->getResponseMetadata();
                    if($responseMetadata->isSetRequestId())  {
                        $listOrderItemsCollection['debug']['RequestId'] = $responseMetadata->getRequestId();
                    }
                }
                $listOrderItemsCollection['debug']['ResponseHeaderMetadata'] = $response->getResponseHeaderMetadata();
            }
        }
        return $listOrderItemsCollection;
    }
}