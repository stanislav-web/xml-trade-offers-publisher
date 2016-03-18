<?php
namespace Application\Aware\Providers;

use Application\Exceptions\BadRequestException;
use Doctrine\Instantiator\Exception\InvalidArgumentException;
use Guzzle\Http\Client;
use Guzzle\Http\Exception\BadResponseException as ResponseException;
use Application\Exceptions\BadResponseException;


/**
 * Class Provider
 *
 * @package Application\Aware\Providers
 */
abstract class Export {

    /**
     * Request options
     *
     * @var array $options
     */
    private $options = [];

    /**
     * Export type
     *
     * @var string $type
     */
    protected $type = 'xml';

    /**
     * Loaded source
     *
     * @var string $source
     */
    private $source;

    /**
     * Request object
     *
     * @var  \Guzzle\Http\Message\Request $request
     */
    protected $request;

    /**
     * Response object
     *
     * @var \Guzzle\Http\Message\Response $response
     */
    protected $response;

    /**
     * Load source with request options
     *
     * @param string $source
     * @param array $options
     * @throws InvalidArgumentException
     */
    public function __construct($source = '', array $options = []) {

        try {
            (!empty($source)) ? $this->setSource($source) : null;
            (!empty($options)) ? $this->setOptions($options) : null;
            $this->setType($options['type']);
        }
        catch(\InvalidArgumentException $e) {
            throw new \InvalidArgumentException($e->getMessage(), $e->getCode());
        }

    }

    /**
     * Set request options
     *
     * @param array $options
     * @throws InvalidArgumentException
     * @return Export
     */
    public function setOptions(array $options = [])
    {
        if(isset($options['request.options']) == true) {
            $this->options = $options['request.options'];
            return $this;
        }

        throw new \InvalidArgumentException('Missed `request.options` param in configuration file', BadRequestException::CODE);
    }

    /**
     * Set target source
     *
     * @param string $source
     * @return Export
     */
    public function setSource($source) {

        $this->source = trim(strip_tags($source));
        return $this;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Export
     */
    public function setType($type) {

        $this->type = trim(strtolower($type));
        return $this;
    }

    /**
     * Load request sources
     *
     * @param $source
     * @return Export
     */
    public function loadSource($source = '') {

        (empty($source) === false) ? $this->setSource($source) : null;
        $client = new Client($this->source, $this->options);
        $this->request = $client->createRequest();

        try {
            $this->response = $this->request->send();
        }
        catch(ResponseException $e) {
            throw new BadResponseException($e->getMessage(), $e->getCode());
        }
        return $this;
    }

    /**
     * Get response data
     *
     * @return \Guzzle\Http\Client
     */
    abstract function getData();
}