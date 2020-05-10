<?php

namespace AppBundle;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Description of AdvancedController
 *
 * @author abdelhak
 */
abstract class AdvancedController extends Controller {

    /**
     *
     * @var EntityManager
     */
    protected $em;

    /**
     *
     * @var JsonResponse
     */
    protected $jsonResponse;

    /**
     *
     * @var Request
     */
    protected $request;

    function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null) {
        parent::setContainer($container);
        $this->em = $this->getDoctrine()->getEntityManager();
        $this->request = $this->get('request_stack')->getCurrentRequest();
        $this->jsonResponse = new JsonResponse();
    }

    /**
     * Send mail
     * @param l'email de l'émetteur $from
     *  @param le nom de l'émetteur $name
     * @param l'email du récepteur $to
     * @param l'objet du mail $object
     * @param le contenu du mail $content
     */
    protected function sendMail($to, $object, $content) {
        $message = \Swift_Message::newInstance()
                ->setSubject($object)
                ->setFrom($this->getParameter("mailer_user"), $this->getParameter("mailer_name"))
                ->setTo($to)
                ->setBody($content)
                ->setContentType("text/html")
        ;
        $this->get('mailer')->send($message);
    }

    /**
     * Convertir un objet ConstraintViolationList à un tableau
     * @param \Symfony\Component\Validator\ConstraintViolationList liste des erreurs
     */
    protected function ConstraintViolationListToArray(\Symfony\Component\Validator\ConstraintViolationList $list) {
        $result = array();
        for ($i = 0; $i < $list->count(); $i++) {
            $result[] = $list->get($i)->getMessage();
        }
        return $result;
    }

}
