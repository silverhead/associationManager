<?php

namespace AppBundle\Event;


use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\EventDispatcher\Event;

class CredentialEvent extends Event
{
    const EVENT_NAME = 'app.security.credential.event';

    private $credentials = array();

    /**
     * @param string $key
     * @param array $credentials
     */
    public function addCredentials(string $key, array $credentials): void
    {
        if(isset($this->credentials[$key])){
            throw new Exception("Credentials with a same key already exist!");
        }

        $this->credentials[$key] = $credentials;
    }

    /**
     * @return array
     */
    public function getCredentialsList(): array
    {
        return $this->credentials;
    }

    /**
     * @param $key
     * @return array|null
     */
    public function getCredentialsByKey($key): ?array
    {
        return isset($this->credentials[$key])?$this->credentials[$key]:null;
    }
}