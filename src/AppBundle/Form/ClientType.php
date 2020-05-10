<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Gregwar\CaptchaBundle\Type\CaptchaType;

class ClientType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('email', EmailType::class, array(
                    'label' => 'Email',
                    "required" => true,
                    "attr" => array("placeholder" => "Email")
                ))
                ->add('password', PasswordType::class, array(
                    'label' => 'Mot de passe',
                    "required" => true,
                    "attr" => array("placeholder" => "Mot de passe")
                ))
                ->add('ville', EntityType::class, array(
                    "required" => true,
                    "label" => "Ville",
                    'class' => 'AppBundle:Ville',
                    'choice_label' => function ($ville) {
                        return $ville->getNom();
                    }
                ))
                ->add('captcha', CaptchaType::class, array(
                    'invalid_message' => 'Le code antispam n\'est pas valide.',
                    "attr" => array("placeholder" => "Code Antispam")
                ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Client'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_client';
    }

}
