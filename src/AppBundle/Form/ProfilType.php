<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Form\ImageType;

class ProfilType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('image', ImageType::class, array("required" => false))
                ->add('nom', TextType::class, array(
                    'label' => 'Nom',
                    "required" => false,
                    "attr" => array("placeholder" => "Nom")
                ))
                ->add('prenom', TextType::class, array(
                    'label' => 'Prénom',
                    "required" => false,
                    "attr" => array("placeholder" => "Prénom")
                ))
                ->add('email', EmailType::class, array(
                    'label' => 'Email',
                    "required" => true,
                    "attr" => array("placeholder" => "Email")
                ))
                ->add('gsm', TextType::class, array(
                    'label' => 'GSM',
                    "required" => false,
                    "attr" => array("placeholder" => "GSM")
                ))
                ->add('ville', EntityType::class, array(
                    "required" => true,
                    "label" => "Ville",
                    'class' => 'AppBundle:Ville',
                    'choice_label' => function ($ville) {
                        return $ville->getNom();
                    }
                ))
                ->add('adresse', TextareaType::class, array(
                    'label' => 'Adresse',
                    "required" => false
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
