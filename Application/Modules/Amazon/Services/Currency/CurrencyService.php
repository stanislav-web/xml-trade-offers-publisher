<?php
namespace Application\Modules\Amazon\Services\Currency;
use Application\Exceptions\NotFoundException;

/**
 * Class CurrencyService
 *
 * @package Application\Modules\Amazon\Services\Currency
 */
class CurrencyService {

    /**
     * API Config
     *
     * @var array Api
     */
    private $apiConfig = null;

    /**
     * Currency data
     *
     * @var string $currency
     */
    private $currency = '';

    /**
     * Init
     *
     * @param string $currency incoming customer data
     * @param array $apiConfig api config
     */
    public function __construct($currency, array $apiConfig) {

        $this->currency = $currency;
        $this->apiConfig = $apiConfig;
    }

    /**
     * Get currency Id
     *
     * @return int
     */
    public function getCurrencyId() {

        if(isset($this->apiConfig["currencies"][$this->currency]) === false) {
            throw new NotFoundException('The currency `'.$this->currency.'` does not found', NotFoundException::CODE);
        }

        return (int)$this->apiConfig["currencies"][$this->currency];
    }

}