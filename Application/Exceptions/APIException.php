<?php
namespace Application\Exceptions;

/**
 * Class APIException
 *
 * Represents an HTTP 400 error.
 * The request could not be understood by the server due to malformed syntax.
 * The client SHOULD NOT repeat the request without modifications.
 *
 * @package Application\Exceptions
 */
class APIException extends \Exception {

    /**
     * @const HTTP response message
     */
    const MESSAGE = 'Api';

    /**
     * @const HTTP response code
     */
    const CODE = 400;

    /**
     * Constructor
     *
     * @param string $message If no message is given 'Bad Request' will be the message
     * @param int $code Status code, defaults to 400
     */
    public function __construct($message = null, $code = null) {

        if(is_null($message) === true && is_null($code) === true) {

            $message = self::MESSAGE;
            $code = self::CODE;
        }

        parent::__construct($message, $code);
    }
}