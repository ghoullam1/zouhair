<?php

namespace AppBundle\Services;

use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of LoginEntryPoint
 *
 * @author abdelhak
 */
class LoginEntryPoint implements AuthenticationEntryPointInterface {

    protected $router;

    public function __construct($router) {
        $this->router = $router;
    }

    /*
     * This method receives the current Request object and the exception by which the exception 
     * listener was triggered. 
     * 
     * The method should return a Response object
     */

    public function start(Request $request, AuthenticationException $authException = null) {

        if ($request->isXmlHttpRequest()) {
            return new Response(json_encode(array(
                        "status_code" => 403,
                        "status_text" => "Forbidden",
                        "exception" => "Vous devez être connecté pour effectuer cette action"
                    )), 403, array("Content-Type" => "application/json"));
        }
        $session = $request->getSession();

        $session->getFlashBag()->add('warning', 'Vous devez être connecté pour accéder à cette page');

        return new RedirectResponse($this->router->generate('login'));
    }

}
