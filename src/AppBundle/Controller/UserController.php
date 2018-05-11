<?php

namespace AppBundle\Controller;

use AppBundle\Event\UserProfileEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("your_profile", name="your_profile")
     */
    public function profileAction()
    {
        $userProfileEvent = new UserProfileEvent();
        $this->get('event_dispatcher')->dispatch(
            UserProfileEvent::EVENT_NAME,
            $userProfileEvent
        );

        $key = $this->getUser()->getDiscr();

        $route = $userProfileEvent->getProfileRouteByKey($key);

        $translator = $this->get('translator');

        if(null === $route){
            $this->addFlash(
                'error',
                $translator->trans('app.user.profileRouteNotFound', array('%discr%', $key)));

            return $this->redirect(
                $this->generateUrl('dashboard')
            );
        }

        // redirect to good profile page in function to type of user
        return $this->redirect(
            $this->generateUrl($route)
        );
    }
}