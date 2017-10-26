<?php
namespace AppBundle\Security;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\HttpFoundation\Request;

class FormAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $userPasswordEncoder;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var Router
     */
    private $router;

    public function __construct(EntityManager $em, Router $router, UserPasswordEncoderInterface $userPasswordEncoder)
    {
        $this->em = $em;
        $this->userPasswordEncoder = $userPasswordEncoder;

        $this->router = $router;
    }

    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != '/login' || !$request->isMethod('POST')) {
            return;
        }

       return [
           'username' => $request->get('username'),
           'password' => $request->get('password'),
       ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['username'];

        return $this->em->getRepository('UserBundle:User')
            ->findByUsernameOrEmail($username);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->userPasswordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

        return new RedirectResponse(
            $this->router->generate('login')
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return new RedirectResponse(
            $this->router->generate('dashboard')
        );
    }

    public function supportsRememberMe()
    {
        return true;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            $this->router->generate('login')
        );
    }

}