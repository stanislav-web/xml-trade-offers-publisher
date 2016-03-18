<?php

namespace Application\Libraries;
use Application\Exceptions\APIException;
use Application\Services\Logger;

/**
 * Class API
 *
 * @package Application\Libraries
 */
class API {

    /**
     * @var
     */
    private $url;

    /**
     * @var
     */
    private $authKey;

    /**
     * @var
     */
    private $method;

    /**
     * @var
     */
    private $params;

    private $response;

    private $token;

    public $isPost = 'POST';

    public $isGet = 'GET';

    private $validHeaders = [
        200, 204
    ];

    public function __construct($token) {
        $this->setToken($token);
    }

    public function call() {

        try {

            $curl = curl_init();
            $header = ['token: ' . $this->getToken()];

            if ($this->getMethod() == 'GET') {
                $this->url = $this->url.'?'.http_build_query($this->getParams());
            }

            curl_setopt($curl, CURLOPT_URL, $this->getUrl());
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_VERBOSE, 1);
            curl_setopt($curl, CURLOPT_HEADER, 1);

            if ($this->getMethod() == 'POST') {
                curl_setopt($curl, CURLOPT_POST, true);
            }
            if ($this->getMethod() == 'PUT') {
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                $header[] = 'X-HTTP-Method-Override: PUT';
            }
            if ($this->getMethod() == 'POST' or $this->getMethod() == 'PUT') {
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($this->getParams()));
            }
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

            $response = curl_exec($curl);

            if(false === $response) {
                throw new APIException(curl_error($curl), curl_errno($curl));
            }


            $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $body = substr($response, $header_size);
            $responseHttpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            if(!in_array($responseHttpCode, $this->validHeaders ) ) {
                $error = curl_error($curl);
                throw new APIException($error, APIException::CODE);
            }

            curl_close($curl);
            return json_decode($body, true);
        }
        catch(\Exception $e) {
            throw new APIException(sprintf('Curl failed with error #%d: %s',
                $e->getCode(), $e->getMessage()), APIException::CODE);
        }
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return 'http://' . $this->url;
    }

    /**
     * @param mixed $url
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @param mixed $authKey
     * @return $this
     */
    public function setAuthKey($authKey)
    {
        $this->authKey = $authKey;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param mixed $params
     * @return $this
     */
    public function setParams($params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     * @return $this
     */
    public function setResponse($response)
    {
        $this->response = $response;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

}