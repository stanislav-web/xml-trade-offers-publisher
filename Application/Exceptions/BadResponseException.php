<?php
namespace Application\Exceptions;

/**
 * Class BadResponseException
 *
 * @package Application\Exceptions
 */
class BadResponseException extends \Exception {

    /**
     * @const HTTP response message
     */
    const MESSAGE = 'Bad Response';

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