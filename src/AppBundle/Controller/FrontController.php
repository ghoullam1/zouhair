<?php

namespace AppBundle\Controller;

use AppBundle\AdvancedController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Cookie;
use AppBundle\Objects\Panier;

/**
 * Description of FrontController
 * 
 * @author abdelhak
 */
class FrontController extends AdvancedController {

    /**
     *
     * @var Session
     */
    protected $session;

    /**
     *
     * @var ParameterBag
     */
    protected $cookies;

    /**
     *
     * @var Panier
     */
    protected $panier;

    function setContainer(\Symfony\Component\DependencyInjection\ContainerInterface $container = null) {
        parent::setContainer($container);
        $this->session = $this->request->getSession();
        $this->cookies = $this->request->cookies;
        $this->panier = $this->cookies->has("panier") ? unserialize($this->cookies->get("panier")) : new Panier($this->em);
    }

    public function loginAction() {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute("app_front_index");
        }
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();

        $lastUsername = $authenticationUtils->getLastUsername();

        $client = new \AppBundle\Entity\Client();
        $form = $this->createForm(\AppBundle\Form\ClientType::class, $client);

        if ($this->request->isMethod("POST") and $this->request->request->has("appbundle_client")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $encoder = $this->container->get('security.password_encoder');
                $password = $encoder->encodePassword($client, $client->getPassword());
                $client->setRoles("ROLE_ADMIN")
                        ->setSalt(md5(uniqid()))
                        ->setPassword($password)
                ;
                $this->em->persist($client);
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Votre compte a été créé avec succès');
            }
        }

        return $this->render("AppBundle:front:login.html.twig", array_merge($this->getDataForLayout(), array(
                    'last_username' => $lastUsername,
                    'error' => $error,
                    "formClient" => $form->createView()
        )));
    }

    /**
     * @Route("/", name="app_front_index")
     */
    public function indexAction() {
        $slides = $this->em->getRepository("AppBundle:Slide")->findBy(array(), array("ordre" => "DESC"));
        $newCollection = $this->em->getRepository("AppBundle:Produit")->newCollection(10);
        $bestSeller = $this->em->getRepository("AppBundle:Produit")->bestSeller(10);
        $soldes = $this->em->getRepository("AppBundle:Produit")->findBy(array("enSolde" => true), array(), 10);
        $bestProduct = $this->em->getRepository("AppBundle:Produit")->findBy(array("isBestProduct" => true), array(), 10);
        $occasions = $this->em->getRepository("AppBundle:Produit")->findBy(array("occasion" => true), array(), 10);
        $deals = $this->em->getRepository("AppBundle:Deal")->getForAccueil();
        return $this->render("AppBundle:front:index.html.twig", array_merge($this->getDataForLayout(), array(
                    "slides" => $slides,
                    "newCollection" => $newCollection,
                    "bestSeller" => $bestSeller,
                    "soldes" => $soldes,
                    "bestProduct" => $bestProduct,
                    "occasions" => $occasions,
                    "deals" => $deals
        )));
    }

    /**
     * @Route("/contact", name="app_front_contact")
     */
    public function contactAction() {
        if ($this->request->isMethod("POST") and $this->request->request->has("contactForm")) {
            $template = $this->em->getRepository("AppBundle:Template")->findOneByLibelle("contact");
            if($template){
                $twig = new \Twig_Environment(new \Twig_Loader_Array([
                    'template' => $template->getContenu(),
                ]));
                $body = $twig->render('template', $this->request->request->get("contactForm"));
            }else{
                $body = implode("<br/>", $this->request->request->get("contactForm"));
            }
            $subject = $template ? $template->getObjet() : "Formulaire de contact";
            $this->sendMail("amine.elalaoui@Zouhair Zapatos.com", $subject, $body);
            $this->request->getSession()->getFlashBag()->add('success', 'Votre message a été bien envoyé');
            return $this->redirectToRoute("app_front_contact");
        }
        return $this->render("AppBundle:front:contact.html.twig", array_merge($this->getDataForLayout(), array(
        )));
    }

    /**
     * @Route("/checkout", name="app_front_checkout")
     */
    public function checkoutAction() {
        $form = $this->createForm(\AppBundle\Form\InfosType::class, $this->getUser());
        if ($this->request->isMethod("POST") and $this->request->request->has("appbundle_client")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $this->em->flush();
                if ($this->panier->getItems()->count() === 0) {
                    $this->request->getSession()->getFlashBag()->add('notice', 'Impossible de valider une commande avec un panier vide!');
                } elseif (is_null($this->panier->getModeLivraison())) {
                    $this->request->getSession()->getFlashBag()->add('notice', 'Vous devez sélectionner un mode de livraison!');
                } else {
                    $commande = $this->generateCommandeFromPanier();
                    $rg = new \AppBundle\Services\Commande\ReferenceGeneration($this->em);
                    $commande->setReference($rg->generateReference());
                    $this->em->persist($commande);
                    $this->em->flush();
                    $statutManager = $this->get('app.commande_historique');
                    $statut = $this->em->getRepository("AppBundle:StatutCommande")->find($this->getParameter('first_statut'));
                    $statutManager->addStatut($commande, $statut);
                    $this->panier->clear();
                    $this->updatePanier();
                    $stockManager = $this->get("app.stock_manager");
                    $stockManager->updateStock($commande);
                    $this->request->getSession()->getFlashBag()->add('success', 'Votre commande a été bien enregistrée');
                    $response = new \Symfony\Component\HttpFoundation\RedirectResponse($this->generateUrl("app_front_compte_commande", array("reference" => $commande->getReference())));
                    $cookie = new Cookie("panier", serialize($this->panier), time() + 60 * 60, '/');
                    $response->headers->setCookie($cookie);
                    return $response;
                }
            }
        }
        $modesLivraison = $this->em->getRepository("AppBundle:ModeLivraison")->findByVille($this->getUser()->getVille());
        return $this->render("AppBundle:front:checkout.html.twig", array_merge($this->getDataForLayout(), array(
                    "formClient" => $form->createView(),
                    "modes" => $modesLivraison
        )));
    }

    private function generateCommandeFromPanier() {
        $commande = new \AppBundle\Entity\Commande();
        $commande->setClient($this->getUser());
        $mode = $this->em->getRepository("AppBundle:ModeLivraison")->find($this->panier->getModeLivraison()['id']);
        $commande->setModeLivraison($mode);
        $commande->setFraisLivraison($mode->getFraisLivraison());
        foreach ($this->panier->getItems() as $item) {
            $produit = $this->em->getRepository("AppBundle:Produit")->findOneByReference($item->getReference());
            $pc = new \AppBundle\Entity\ProduitCommande();
            $pc
                    ->setCommande($commande)
                    ->setPrixUnitaire($item->getPrix())
                    ->setQuantite($item->getQuantite())
                    ->setProduit($produit)
            ;
            $couleur = $item->getCouleur() ? $item->getCouleur()['id'] : null;
            $taille = $item->getTaille() ? $item->getTaille()['id'] : null;
            $variation = $this->em->getRepository("AppBundle:ProduitVariation")->findOneBy(array(
                "produit" => $produit,
                "couleur" => $couleur,
                "taille" => $taille
            ));
            $pc->setVariation($variation);
            $commande->addProduit($pc);
        }
        if (!is_null($this->panier->getCoupon())) {
            $coupon = $this->em->getRepository("AppBundle:Coupon")->find($this->panier->getCoupon()['id']);
            $commande->setCoupon($coupon);
            $commande->setRemise($this->panier->getRemise());
        }
        $commande->setTotal($this->panier->getTotal());
        return $commande;
    }

    /**
     * @Route("/compte", name="app_front_compte")
     */
    public function compteAction() {
        $form = $this->createForm(\AppBundle\Form\ProfilType::class, $this->getUser());
        if ($this->request->isMethod("POST") and $this->request->request->has("appbundle_client")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                if ($this->request->request->has("check_image") and $this->request->request->get("check_image") === "true") {
                    if (!is_null($this->getUser()->getImage()) and ! is_null($this->getUser()->getImage()->getId())) {
                        if (!is_null($this->getUser()->getImage()->getFile())) {
                            $this->getUser()->getImage()->upload();
                        } else {
                            $this->em->remove($this->getUser()->getImage());
                            $this->getUser()->setImage();
                        }
                    }
                }
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Informations enregistrées avec succès');
            }
        }
        $commandes = $this->em->getRepository("AppBundle:Commande")->findBy(array("client" => $this->getUser()), array("dateCommande" => "DESC"));
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $commandes, $this->request->query->getInt("page", 1), 10
        );
        $pagination->setTemplate("twitter_bootstrap_v3_pagination.html.twig");
        return $this->render("AppBundle:front:compte.html.twig", array_merge($this->getDataForLayout(), array(
                    "pagination" => $pagination,
                    "formClient" => $form->createView()
        )));
    }

    /**
     * @Route("/compte/commande/{reference}", name="app_front_compte_commande")
     */
    public function commandeAction($reference) {

        $commande = $this->em->getRepository("AppBundle:Commande")->findOneByReference($reference);
        if (!$commande)
            throw $this->createNotFoundException('Commande demandée n\'existe pas !');
        $commentaire = new \AppBundle\Entity\Commentaire();
        $form = $this->createForm(\AppBundle\Form\CommentaireType::class, $commentaire);
        if ($this->request->isMethod("POST") and $this->request->request->has("appbundle_commentaire")) {
            $form->handleRequest($this->request);
            if ($form->isValid()) {
                $commentaire
                        ->setClient($this->getUser())
                        ->setCommande($commande);
                $this->em->persist($commentaire);
                $this->em->flush();
                $this->request->getSession()->getFlashBag()->add('success', 'Commentaire ajouté avec succès');
            }
        }
        $commentaires = $this->em->getRepository("AppBundle:Commentaire")->findBy(array("commande" => $commande->getId()), array("dateCommentaire" => "DESC"));
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $commentaires, $this->request->query->getInt("page", 1), 6
        );
        $pagination->setTemplate("twitter_bootstrap_v3_pagination.html.twig");
        return $this->render("AppBundle:front:compte_commande.html.twig", array_merge($this->getDataForLayout(), array(
                    "commande" => $commande,
                    "formCommentaire" => $form->createView(),
                    "pagination" => $pagination
        )));
    }

    /**
     * @Route("/produits/{slug}/{id_genre}", name="app_front_produits")
     */
    public function produitsAction($slug, $id_genre = null) {
        $categorie = $this->em->getRepository("AppBundle:Categorie")->findOneBySlug($slug);
        $genre = $this->em->getRepository("AppBundle:Genre")->find($id_genre ? $id_genre : 0);
        if (is_null($categorie))
            throw $this->createNotFoundException('Catégorie demandée n\'existe pas !');

        $this->request->query->add(array(
            "l" => $this->request->query->getInt("l", 5)
        ));

        $criteres = $this->request->query->all();
        $criteres["c"] = $categorie->getSlug();
        if ($genre)
            $criteres["g"] = $genre->getId();
        $query = $this->em->getRepository("AppBundle:Produit")->listeFrontOffice($criteres);
        $nbrParPage = $this->request->query->getInt("l");
        $page = $this->request->query->getInt("page", 1);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $query, $page, $nbrParPage
        );
        $pagination->setTemplate("twitter_bootstrap_v3_pagination.html.twig");
        if (($page > $pagination->getPageCount() and $pagination->getPageCount() >= 1) or $page < 1)
            throw $this->createNotFoundException("Le contenu demandé n'existe pas !");
        $sideCategories = $genre ? $this->em->getRepository("AppBundle:Categorie")->findForGenre($genre->getId()) : null;
        $sideMarques = $this->em->getRepository("AppBundle:Marque")->getForCategorie($categorie->getId());
        $MinAndMaxPrice = $this->em->getRepository("AppBundle:Produit")->minAndMaxPrice($criteres);
        $bestSeller = $this->em->getRepository("AppBundle:Produit")->bestSeller();
        return $this->render("AppBundle:front:produits.html.twig", array_merge($this->getDataForLayout(), array(
                    "sideCategories" => $sideCategories,
                    "sideMarques" => $sideMarques,
                    "categorie" => $categorie,
                    "genre" => $genre,
                    "pagination" => $pagination,
                    "filterPrice" => $MinAndMaxPrice,
                    "criteres" => $criteres,
                    "bestSeller" => $bestSeller
        )));
    }

    /**
     * @Route("/produit/{reference}", name="app_front_produits_details")
     */
    public function produitAction($reference) {
        $produit = $this->em->getRepository("AppBundle:Produit")->findOneByReference($reference);

        if (!$produit)
            throw $this->createNotFoundException('Produit demandé n\'existe pas !');

        $produitsLies = $this->em->getRepository("AppBundle:Produit")->getProduitsLies($produit);
        $commentaire = new \AppBundle\Entity\Commentaire();
        $form = $this->createForm(\AppBundle\Form\CommentaireType::class, $commentaire);
        $couleursDisponible = $this->em->getRepository("AppBundle:Couleur")->couleurDispoPourProduit($produit->getReference());
        $taillesDisponible = $this->em->getRepository("AppBundle:Taille")->tailleDispoPourProduit($produit->getReference());

        if ($this->request->isMethod("POST") and $this->request->request->has("appbundle_commentaire")) {
            if ($this->getUser()->productCommented($produit->getReference())) {
                $this->request->getSession()->getFlashBag()->add('notice', 'Vous avez dèjà commenter ce produit!');
            } else {
                $form->handleRequest($this->request);
                if ($form->isValid()) {
                    $commentaire->setProduit($produit);
                    $commentaire->setClient($this->getUser());
                    $this->em->persist($commentaire);
                    $this->em->flush();
                    $this->request->getSession()->getFlashBag()->add('success', 'Commentaire ajouté avec succès');
                }
            }
            return $this->redirectToRoute("app_front_produits_details", array("reference" => $reference));
        }
        $commentaires = $this->em->getRepository("AppBundle:Commentaire")->findBy(array("produit" => $produit->getId()), array("dateCommentaire" => "DESC"));
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
                $commentaires, $this->request->query->getInt("page", 1), 6
        );
        $pagination->setTemplate("twitter_bootstrap_v3_pagination.html.twig");
        return $this->render("AppBundle:front:produits_details.html.twig", array_merge($this->getDataForLayout(), array(
                    "produit" => $produit,
                    "couleurs" => $couleursDisponible,
                    "tailles" => $taillesDisponible,
                    "pagination" => $pagination,
                    "produitsLies" => $produitsLies,
                    "formCommentaire" => $form->createView()
        )));
    }

    /**
     * @Route("/panier/details", name="app_front_panier_details")
     */
    public function panierAction() {


        return $this->render("AppBundle:front:panier_details.html.twig", array_merge($this->getDataForLayout(), array()));
    }

    private function getDataForLayout() {
        $data = array();
        $data["panier"] = $this->panier;
        $data["genres"] = $this->em->getRepository("AppBundle:Genre")->findBy(array(), array("ordre" => "ASC"));
        $data["marques"] = $this->em->getRepository("AppBundle:Marque")->getForLayout();
        $data["nbrClients"] = $this->em->getRepository("AppBundle:Client")->countMe();
        $data["nbrProduits"] = $this->em->getRepository("AppBundle:Produit")->countMe();
        $data["nbrCommandes"] = $this->em->getRepository("AppBundle:Commande")->countMe();
        $data["nbrMarques"] = $this->em->getRepository("AppBundle:Marque")->countMe();
        $data["commentaires"] = $this->em->getRepository("AppBundle:Commentaire")->getCommandesCommentaires();

        return $data;
    }

    private function updatePanier() {
        $this->jsonResponse->headers->clearCookie("panier");
        $cookie = new Cookie("panier", serialize($this->panier), time() + 60 * 60, '/');
        $this->jsonResponse->headers->setCookie($cookie);
    }

    /**
     * @Route("/availableSizes", name="app_front_available_sizes")
     */
    public function getAvailableSizes() {
        if ($this->request->isMethod("POST") and $this->request->isXmlHttpRequest() and $this->request->request->has("reference") and $this->request->request->has("couleur")) {
            $sizes = $this->em->getRepository("AppBundle:Taille")->tailleDispoPourProduit($this->request->request->get("reference"), $this->request->request->get("couleur"));
            $arraySizes = array();
            foreach ($sizes as $size) {
                $arraySizes[] = array(
                    "id" => $size->getId(),
                    "nom" => $size->getNom()
                );
            }
            return $this->jsonResponse->setData($arraySizes);
        }
        throw $this->createNotFoundException("Url demandé n'est pas accessible");
    }

    /**
     * @Route("/availableColors", name="app_front_available_colors")
     */
    public function getAvailableColors() {
        if ($this->request->isMethod("POST") and $this->request->isXmlHttpRequest() and $this->request->request->has("reference") and $this->request->request->has("taille")) {
            $couleurs = $this->em->getRepository("AppBundle:Couleur")->couleurDispoPourProduit($this->request->request->get("reference"), $this->request->request->get("taille"));
            $arrayCouleurs = array();
            foreach ($couleurs as $couleur) {
                $arrayCouleurs[] = array(
                    "id" => $couleur->getId(),
                    "nom" => $couleur->getNom()
                );
            }
            return $this->jsonResponse->setData($arrayCouleurs);
        }
        throw $this->createNotFoundException("Url demandé n'est pas accessible");
    }

    /**
     * @Route("/panier/add", name="app_front_panier_add")
     */
    public function addPanierAction() {
        if ($this->request->isMethod("POST") and $this->request->isXmlHttpRequest()) {
            $reference = $this->request->request->get("reference", "reference");
            $produit = $this->em->getRepository("AppBundle:Produit")->findOneByReference($reference);
            if (!$produit)
                return $this->jsonResponse->setData(array("code" => 0, "msg" => "Produit Introuvable!"));

            $quantite = $this->request->request->getInt("quantite", 1);
            $couleur = $this->request->request->get("couleur", null);
            $taille = $this->request->request->get("taille", null);
            $panierManager = $this->get("app.front.panier_manager");
            $panierManager->addItemToPanier($this->panier, $produit, $quantite, $couleur, $taille);
            $this->updatePanier();
            $panierHtml = $this->renderView("AppBundle:front:panier.html.twig", array("panier" => $this->panier));
            return $this->jsonResponse->setData(array(
                        "code" => $panierManager->getCode(),
                        "msg" => $panierManager->getMsg(),
                        "panier" => $panierHtml,
            ));
        }
        throw $this->createNotFoundException("Url demandé n'est pas accessible");
    }

    /**
     * @Route("/panier/remove", name="app_front_panier_remove")
     */
    public function removePanierAction() {
        if ($this->request->isMethod("POST") and $this->request->isXmlHttpRequest()) {
            $reference = $this->request->request->get("reference", "reference");
            $couleur = $this->request->request->get("couleur", null);
            $taille = $this->request->request->get("taille", null);

            $panierManager = $this->get("app.front.panier_manager");
            $panierManager->removeItemFromPanier($this->panier, $reference, $couleur, $taille);
            $this->updatePanier();
            if ($this->request->server->has("HTTP_REFERER") and strpos($this->request->server->get("HTTP_REFERER"), "/panier/details") !== false)
                $panierHtml = $this->renderView("AppBundle:front:inc_panier_details.html.twig", array("panier" => $this->panier));
            else
                $panierHtml = $this->renderView("AppBundle:front:panier.html.twig", array("panier" => $this->panier));
            return $this->jsonResponse->setData(array(
                        "code" => $panierManager->getCode(),
                        "msg" => $panierManager->getMsg(),
                        "panier" => $panierHtml,
            ));
        }
        throw $this->createNotFoundException("Url demandé n'est pas accessible");
    }

    /**
     * @Route("/panier/clear", name="app_front_panier_clear")
     */
    public function clearPanierAction() {
        if ($this->request->isMethod("POST") and $this->request->isXmlHttpRequest()) {
            $this->panier->clear();
            $this->updatePanier();
            $panierDetailsHtml = $this->renderView("AppBundle:front:inc_panier_details.html.twig", array("panier" => $this->panier));
            return $this->jsonResponse->setData(array(
                        "code" => 1,
                        "msg" => "Panier vidé avec succès",
                        "panier" => $panierDetailsHtml
            ));
        }
        throw $this->createNotFoundException("Url demandé n'est pas accessible");
    }

    /**
     * @Route("/panier/inc", name="app_front_panier_inc")
     */
    public function incPanierAction() {
        if ($this->request->isMethod("POST") and $this->request->isXmlHttpRequest()) {
            $reference = $this->request->request->get("reference", "reference");
            $couleur = $this->request->request->get("couleur", null);
            $taille = $this->request->request->get("taille", null);

            $panierManager = $this->get("app.front.panier_manager");
            $panierManager->augmenteQuantite($this->panier, $reference, $couleur, $taille);
            $this->updatePanier();

            $panierDetailsHtml = $this->renderView("AppBundle:front:inc_panier_details.html.twig", array("panier" => $this->panier));
            return $this->jsonResponse->setData(array(
                        "code" => $panierManager->getCode(),
                        "msg" => $panierManager->getMsg(),
                        "panier" => $panierDetailsHtml
            ));
        }
        throw $this->createNotFoundException("Url demandé n'est pas accessible");
    }

    /**
     * @Route("/panier/dec", name="app_front_panier_dec")
     */
    public function decPanierAction() {
        if ($this->request->isMethod("POST") and $this->request->isXmlHttpRequest()) {
            $reference = $this->request->request->get("reference", "reference");
            $couleur = $this->request->request->get("couleur", null);
            $taille = $this->request->request->get("taille", null);

            $panierManager = $this->get("app.front.panier_manager");
            $panierManager->deminuerQuantite($this->panier, $reference, $couleur, $taille);
            $this->updatePanier();

            $panierDetailsHtml = $this->renderView("AppBundle:front:inc_panier_details.html.twig", array("panier" => $this->panier));
            return $this->jsonResponse->setData(array(
                        "code" => $panierManager->getCode(),
                        "msg" => $panierManager->getMsg(),
                        "panier" => $panierDetailsHtml
            ));
        }
        throw $this->createNotFoundException("Url demandé n'est pas accessible");
    }

    /**
     * @Route("/panier/coupon/add", name="app_front_panier_coupon_add")
     */
    public function panierCouponAddAction() {
        if ($this->request->isMethod("POST") and $this->request->isXmlHttpRequest()) {
            $code = $this->request->request->get("code", "fauxCode");


            $panierManager = $this->get("app.front.panier_manager");
            $panierManager->addCoupon($this->panier, $code);
            $this->updatePanier();
            $panierDetailsHtml = $this->renderView("AppBundle:front:inc_panier_details.html.twig", array("panier" => $this->panier));
            return $this->jsonResponse->setData(array(
                        "code" => $panierManager->getCode(),
                        "msg" => $panierManager->getMsg(),
                        "panier" => $panierDetailsHtml
            ));
        }
        throw $this->createNotFoundException("Url demandé n'est pas accessible");
    }

    /**
     * @Route("/panier/coupon/remove", name="app_front_panier_coupon_remove")
     */
    public function panierCouponRemoveAction() {
        if ($this->request->isMethod("POST") and $this->request->isXmlHttpRequest()) {
            $panierManager = $this->get("app.front.panier_manager");
            $panierManager->removeCoupon($this->panier);
            $this->updatePanier();
            $panierDetailsHtml = $this->renderView("AppBundle:front:inc_panier_details.html.twig", array("panier" => $this->panier));
            return $this->jsonResponse->setData(array(
                        "code" => $panierManager->getCode(),
                        "msg" => $panierManager->getMsg(),
                        "panier" => $panierDetailsHtml
            ));
        }
        throw $this->createNotFoundException("Url demandé n'est pas accessible");
    }

    /**
     * @Route("/panier/modeLivraison/add", name="app_front_panier_mode_livraison_add")
     */
    public function panierModeLivraisonAddAction() {
        if ($this->request->isMethod("POST") and $this->request->isXmlHttpRequest()) {
            $id = $this->request->request->get("mode", 0);
            $panierManager = $this->get("app.front.panier_manager");
            ($id === '-1') ? $panierManager->removeModeLivraison($this->panier) : $panierManager->addModeLivraison($this->panier, $id);
            $this->updatePanier();
            $panierDetailsHtml = $this->renderView("AppBundle:front:inc_checkout_details.html.twig", array("panier" => $this->panier));
            return $this->jsonResponse->setData(array(
                        "code" => $panierManager->getCode(),
                        "msg" => $panierManager->getMsg(),
                        "panier" => $panierDetailsHtml
            ));
        }
        throw $this->createNotFoundException("Url demandé n'est pas accessible");
    }

    /**
     * @Route("/newsletter/inscription", name="app_front_newsletter_inscription")
     */
    public function newsletterInscriptionAction() {
        if ($this->request->isMethod("POST") and $this->request->isXmlHttpRequest()) {
            $email = $this->request->request->get("email", "email");

            $newsLetter = new \AppBundle\Entity\NewsLetter();
            $newsLetter->setEmail($email);
            $validator = $this->get('validator');
            $errors = $validator->validate($newsLetter);
            if (count($errors) > 0) {
                return $this->jsonResponse->setData(array(
                            "code" => -1,
                            "msg" => $this->ConstraintViolationListToArray($errors)[0]
                ));
            }
            $this->em->persist($newsLetter);
            $this->em->flush();
            return $this->jsonResponse->setData(array(
                        "code" => 1,
                        "msg" => "Inscription réussite"
            ));
        }
        throw $this->createNotFoundException("Url demandé n'est pas accessible");
    }

}
