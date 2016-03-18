<?php
namespace Application\Exceptions;

/**
 * Class ClientProviderException
 *
 * @package Application\Exceptions
 */
class ClientProviderException extends \Exception {

    /**
     * @const HTTP response message
     */
    const MESSAGE = 'Client provider error';

    /**
     * @const HTTP response code
     */
    const CODE = 500;

    /**
     * Constructor
     *
     * @param array $data additional info
     * @param string $message If no message is given 'Bad Request' will be the message
     * @param int $code Status code, defaults to 400
     */
    public function __construct(array $data = [], $message = null, $code = null) {

        if(is_null($message) === true && is_null($code) === true) {

            $message = self::MESSAGE;
            $code = self::CODE;
        }

        parent::__construct($message, $code, $data);
    }
}