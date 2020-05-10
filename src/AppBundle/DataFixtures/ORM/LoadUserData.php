<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Client;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Description of LoadUserData
 *
 * @author abdelhak
 */
class LoadUserData implements FixtureInterface, ContainerAwareInterface {

    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null) {
        $this->container = $container;
    }

    public function load(ObjectManager $manager) {
        $client = new \AppBundle\Entity\Client();
        $encoder = $this->container->get('security.password_encoder');
        $password = $encoder->encodePassword($client, '@7i9ovic91#');
        $client->setAdresse("Lot hassania 1 rue 43 Nr 34 El Oulfa")
                ->setEmail("abdelhak.ouaddi@gmail.com")
                ->setGsm("0630573343")
                ->setNom("Ouaddi")
                ->setPrenom("Abdelhak")
                ->setPassword($password)
                ->setSalt(md5(uniqid()))
                ->setRoles(array(
                    "ROLE_SUPER_ADMIN"
                ))
        ;
        $manager->persist($client);
        $manager->flush();
    }

}
