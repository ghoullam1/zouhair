<?php

namespace AppBundle\Services;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Security;

class AuthenticationHandler implements AuthenticationSuccessHandlerInterface, AuthenticationFailureHandlerInterface, LogoutSuccessHandlerInterface {

    private $router;
    private $session;
    private $manager;
    private $tokenStorage;

    /**
     * Constructor
     *
     * @author 	Joe Sexton <joe@webtipblog.com>
     * @param 	RouterInterface $router
     * @param 	Session $session
     */
    public function __construct(RouterInterface $router, Session $session, EntityManager $manager, TokenStorage $tokenStorage) {
        $this->router = $router;
        $this->session = $session;
        $this->manager = $manager;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * onAuthenticationSuccess
     *
     * @author 	Joe Sexton <joe@webtipblog.com>
     * @param 	Request $request
     * @param 	TokenInterface $token
     * @return 	Response
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {
        $user = $this->tokenStorage->getToken()->getUser();
        $user->setLastLogin(new \DateTime());
        $this->manager->flush();
        if ($request->isXmlHttpRequest()) {
            $url = "";
            if ($this->session->get('_security.main.target_path')) {
                $url = $this->session->get('_security.main.target_path');
            } else {
                $url = $this->router->generate('app_front_index');
            }
            $this->session->set('_security.main.target_path', null);
            $array = array('success' => true, 'code' => 1, 'url' => $url);
            $response = new Response(json_encode($array));
            $response->headers->set('Content-Type', 'application/json');
            return $response;

            // if form login 
        } else {

            if ($this->session->get('_security.main.target_path')) {

                $url = $this->session->get('_security.main.target_path');
            } else {

                $url = $this->router->generate('app_front_index');
            } // end if

            return new RedirectResponse($url);
        }
    }

    /**
     * onAuthenticationFailure
     *
     * @author 	Joe Sexton <joe@webtipblog.com>
     * @param 	Request $request
     * @param 	AuthenticationException $exception
     * @return 	Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        // if AJAX login
        if ($request->isXmlHttpRequest()) {

            $array = array('success' => false, 'message' => $exception->getMessage(), 'code' => 0); // data to return via JSON
            $response = new Response(json_encode($array));
            $response->headers->set('Content-Type', 'application/json');
            return $response;

            // if form login 
        } else {

            // set authentication exception to session
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);

            return new RedirectResponse($this->router->generate('login'));
        }
    }

    public function onLogoutSuccess(Request $request) {
        $this->session->set('_security.main.target_path', null);

        $url = $this->router->generate('app_front_index');
        return new RedirectResponse($url);
    }

}
