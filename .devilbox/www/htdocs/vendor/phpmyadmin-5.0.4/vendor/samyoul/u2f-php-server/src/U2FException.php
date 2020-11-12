<?php
/**
 * Created by IntelliJ IDEA.
 * User: samuel
 * Date: 09/12/2016
 * Time: 14:51
 */

namespace Samyoul\U2F\U2FServer;


class U2FException extends \Exception
{
    /** Error for the authentication message not matching any outstanding
     * authentication request */
    const NO_MATCHING_REQUEST = 1;

    /** Error for the authentication message not matching any registration */
    const NO_MATCHING_REGISTRATION = 2;

    /** Error for the signature on the authentication message not verifying with
     * the correct key */
    const AUTHENTICATION_FAILURE = 3;

    /** Error for the challenge in the registration message not matching the
     * registration challenge */
    const UNMATCHED_CHALLENGE = 4;

    /** Error for the attestation signature on the registration message not
     * verifying */
    const ATTESTATION_SIGNATURE = 5;

    /** Error for the attestation verification not verifying */
    const ATTESTATION_VERIFICATION = 6;

    /** Error for not getting good random from the system */
    const BAD_RANDOM = 7;

    /** Error when the counter is lower than expected */
    const COUNTER_TOO_LOW = 8;

    /** Error decoding public key */
    const PUBKEY_DECODE = 9;

    /** Error user-agent returned error */
    const BAD_UA_RETURNING = 10;

    /** Error old OpenSSL version */
    const OLD_OPENSSL = 11;

    /**
     * Override constructor and make message and code mandatory
     * @param string $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct($message, $code, \Exception $previous = null) {
        parent::__construct($message, $code, $previous);
    }
}