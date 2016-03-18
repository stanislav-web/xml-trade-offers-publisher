<?php
namespace Application\Exceptions;

/**
 * Class NotFoundException
 *
 * Represents an HTTP 404 error.
 * The server has not found anything matching the Request-URI.
 * No indication is given of whether the condition is temporary or permanent.
 *
 * @package Application\Exceptions
 */
class NotFoundException extends \Exception {

    /**
     * @const HTTP response message
     */
    const MESSAGE = 'Not Found';

    /**
     * @const HTTP response code
     */
    const CODE = 404;

    /**
     * Constructor
     *
     * @param string $message If no message is given 'Not Found' will be the message
     * @param int $code Status code, defaults to 404
     */
    public function __construct($message = null, $code = null) {

        if(is_null($message) === true && is_null($code) === true) {

            $message = self::MESSAGE;
            $code = self::CODE;
        }

        parent::__construct($message, $code);
    }
}