<?php

namespace AppBundle\Event;

use Symfony\Component\EventDispatcher\Event;


class UserProfileEvent extends Event
{
    const EVENT_NAME = 'app.user.profile_route.event';

    private $profileRoutes = array();

    /**
     * @param string $key
     * @param array $credentials
     */
    public function addProfileRoute(string $key, $profileRoute): void
    {
        if(isset($this->profileRoutes[$key])){
            throw new Exception("Route with a same key already exist!");
        }

        $this->profileRoutes[$key] = $profileRoute;
    }

    /**
     * @param $key
     * @return array|null
     */
    public function getProfileRouteByKey($key):? string
    {
        return isset($this->profileRoutes[$key])?$this->profileRoutes[$key]:null;
    }
}