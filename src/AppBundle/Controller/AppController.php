<?php

namespace AppBundle\Controller;

use AppBundle\AdvancedController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Description of AppController
 *
 * @author abdelhak
 */
class AppController extends AdvancedController {

    /**
     * @Route("/administration", name="app_admin_index")
     */
    public function adminAction() {
        return $this->render("AppBundle:admin:index.html.twig");
    }

    /**
     * retourner l'image
     * @Route("/images/{nom}", name="app_image")
     */
    public function imageAction($nom) {
        $image = $this->em->getRepository("AppBundle:Image")->findOneByNom($nom);
        if (!$image) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException("L'image demandée n'existe pas");
        }
        $file = $this->getParameter("web_dir") . "images/upload/" . $image->getFullName();
        if (!file_exists($file) or ! is_file($file)) {
            throw new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException("Impossible de trouver le fichier demandée");
        }
        return new \Symfony\Component\HttpFoundation\Response(file_get_contents($file), 200, array("Content-Type" => mime_content_type($file)));
    }

}
