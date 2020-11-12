<?php
/**
 * Created by IntelliJ IDEA.
 * User: samuel
 * Date: 09/12/2016
 * Time: 14:48
 */
namespace Samyoul\U2F\U2FServer;


class RegistrationRequest implements \JsonSerializable
{
    /** Protocol version */
    protected $version = U2FServer::VERSION;

    /** Registration challenge */
    protected $challenge;

    /** Application id */
    protected $appId;

    /**
     * @param string $challenge
     * @param string $appId
     */
    public function __construct($challenge, $appId)
    {
        $this->challenge = $challenge;
        $this->appId = $appId;
    }

    public function version()
    {
        return $this->version;
    }

    public function challenge()
    {
        return $this->challenge;
    }

    public function appId()
    {
        return $this->appId;
    }

    public function jsonSerialize()
    {
        return [
            'version' => $this->version,
            'challenge' => $this->challenge,
            'appId' => $this->appId,
        ];
    }

}