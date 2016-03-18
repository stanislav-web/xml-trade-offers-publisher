<?php
namespace Application\Models;

/**
 * Class OrderModel
 *
 * @package Application\Models
 */
class OrderModel {

    private $customerId = null;

    private $addressId = null;

    private $paymentMethodId = null;

    private $shippingMethodId = null;

    private $checkoutId = null;

    private $warehouseId = null;

    private $currencyId = null;


    /**
     * @param array $order
     */
    public function __construct(array $order) {

        var_dump($order); exit;
    }

    /**
     * @return mixed
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * @param mixed $customerId
     * @return OrderModel
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddressId()
    {
        return $this->addressId;
    }

    /**
     * @param mixed $addressId
     * @return OrderModel
     */
    public function setAddressId($addressId)
    {
        $this->addressId = $addressId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPaymentMethodId()
    {
        return $this->paymentMethodId;
    }

    /**
     * @param mixed $paymentMethodId
     * @return OrderModel
     */
    public function setPaymentMethodId($paymentMethodId)
    {
        $this->paymentMethodId = $paymentMethodId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getShippingMethodId()
    {
        return $this->shippingMethodId;
    }

    /**
     * @param mixed $shippingMethodId
     * @return OrderModel
     */
    public function setShippingMethodId($shippingMethodId)
    {
        $this->shippingMethodId = $shippingMethodId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCheckoutId()
    {
        return $this->checkoutId;
    }

    /**
     * @param mixed $checkoutId
     * @return OrderModel
     */
    public function setCheckoutId($checkoutId)
    {
        $this->checkoutId = $checkoutId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWarehouseId()
    {
        return $this->warehouseId;
    }

    /**
     * @param mixed $warehouseId
     * @return OrderModel
     */
    public function setWarehouseId($warehouseId)
    {
        $this->warehouseId = $warehouseId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCurrencyId()
    {
        return $this->currencyId;
    }

    /**
     * @param mixed $currencyId
     * @return OrderModel
     */
    public function setCurrencyId($currencyId)
    {
        $this->currencyId = $currencyId;
        return $this;
    }
}