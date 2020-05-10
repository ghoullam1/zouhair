<?php

namespace AppBundle\Services\Commande;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Commande;
use AppBundle\Entity\StatutCommande;
use AppBundle\Entity\CommandeHistorique;

/**
 * Description of CommandeStatutManager
 *
 * @author abdelhak
 */
class CommandeStatutManager {

    /**
     *
     * @var EntityManager
     */
    private $em;

    /**
     *
     * @var string
     */
    private $mailUser;

    /**
     *
     * @var \Swift_Mailer
     */
    private $mailer;

    function __construct(EntityManager $em, $mailUser, \Swift_Mailer $mailer) {
        $this->em = $em;
        $this->mailUser = $mailUser;
        $this->mailer = $mailer;
    }

    function addStatut(Commande $commande, StatutCommande $statut) {
        $ch = new CommandeHistorique();
        $ch
                ->setCommande($commande)
                ->setStatut($statut)
        ;
        $this->em->persist($ch);
        $this->em->flush();
        if ($statut->getSendMail())
            $this->sendMail($commande, $statut);
    }

    function sendMail(Commande $commande, StatutCommande $statut) {
        $twig = new \Twig_Environment(new \Twig_Loader_String());
        if ($statut->getSendMail()) {
            $message = \Swift_Message::newInstance()
                    ->setSubject("Commande " . $statut->getLibelle())
                    ->setFrom($this->mailUser, "Web Stroe")
                    ->setTo($commande->getClient()->getEmail())
                    ->setBody($twig->render($statut->getTemplate(), array(
                                "commande" => $commande
                    )))
                    ->setContentType("text/html")
            ;
            $sender =  $this->mailer->send($message);
            sleep(3);
        }
    }

}
