<?php

namespace AppBundle\Event;


use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\EventDispatcher\Event;

class MenuCredentialEvent extends Event
{
    const EVENT_NAME = 'app.security.menu.credential.event';

    private $credentials = array();

    /**
     * @param string $key
     * @param array $credentials
     */
    public function addCredentials(array $credentials): void
    {
        $this->credentials = array_merge($this->credentials, $credentials);
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