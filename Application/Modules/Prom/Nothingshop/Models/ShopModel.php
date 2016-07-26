<?php
namespace Application\Modules\Prom\Nothingshop\Models;
use Application\Aware\Providers\Model;

/**
 * Class ShopModel
 *
 * @package Application\Modules\Prom\Nothingshop\Models
 */
class ShopModel extends Model {

    /**
     * Shop name
     *
     * @var string $name
     */
    private $name = '';

    /**
     * Shop url
     *
     * @var string $url
     */
    private $url = '';

    /**
     * Shop encoding
     *
     * @var string $encoding
     */
    private $encoding = 'UTF-8';

    /**
     * Shop currency
     *
     * @var string $currency
     */
    private $currency = null;

    /**
     * Init model
     *
     * @param string $name
     * @param string $url
     * @param string $encoding
     * @param string $currency
     */
    public function __construct($name, $url, $encoding, $currency) {

        $this->setName($name)
            ->setUrl($url)
            ->setEncoding($encoding)
            ->setCurrency($currency);
    }

    /**
     * Validate shop name
     *
     * @param string $name
     * @return ShopModel
     */
    private function setName($name) {

        $this->name = $name;
        return $this;
    }

    /**
     * Validate shop url
     *
     * @param string $url
     * @return ShopModel
     */
    private function setUrl($url) {

        $this->url = $url;
        return $this;
    }

    /**
     * Validate encoding
     *
     * @param string $encoding
     * @return ShopModel
     */
    private function setEncoding($encoding) {

        $this->encoding = $encoding;
        return $this;
    }

    /**
     * Validate currency
     *
     * @param string $currency
     * @return ShopModel
     */
    private function setCurrency($currency) {

        $this->currency = $currency;
        return $this;
    }

    /**
     * Reverse object to real array for all public properties
     *
     * @return array
     */
    public function toArray() {
        return  get_object_vars($this);
    }
}