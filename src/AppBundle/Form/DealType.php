<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use AppBundle\Form\ImageType;

class DealType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('active', CheckboxType::class, array(
                    "label" => "Activé",
                    "value" => true,
                    "required" => false
                ))
                ->add('dateDebut', DateTimeType::class, array(
                    'format' => 'dd/MM/yyyy H:mm',
                    'widget' => 'single_text',
                    'html5' => false,
                    "required" => true,
                    "label" => "Date Début"
                ))
                ->add('dateFin', DateTimeType::class, array(
                    'format' => 'dd/MM/yyyy H:mm',
                    'widget' => 'single_text',
                    'html5' => false,
                    "required" => true,
                    "label" => "Date Fin"
                ))
                ->add('titre', TextType::class, array(
                    "label" => "Titre",
                    "required" => false,
                    "attr" => array(
                        "placeholder" => "Titre"
                    )
                ))
                ->add('description', TextareaType::class, array(
                    "label" => "Déscription",
                    "required" => false,
                    "attr" => array(
                        "placeholder" => "Déscription"
                    )
                ))
                ->add('categorie', EntityType::class, array(
                    "required" => false,
                    "label" => "Catégorie",
                    'class' => 'AppBundle:Categorie',
                    'choice_label' => function ($categorie) {
                        return $categorie->getLibelle();
                    }
                ))
                ->add('produit', EntityType::class, array(
                    "required" => false,
                    "label" => "Produit",
                    'class' => 'AppBundle:Produit',
                    'choice_label' => function ($produit) {
                        return $produit->getReference() . " - " . $produit->getTitre();
                    }
                ))
                ->add('lien', UrlType::class, array(
                    "label" => "Lien",
                    "required" => false,
                    "attr" => array(
                        "placeholder" => "Lien de redirection"
                    )
                ))
                ->add('newTab', CheckboxType::class, array(
                    "label" => "Nouvel onglet",
                    "value" => true,
                    "required" => false
                ))
                ->add('image', ImageType::class, array("required" => true))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Deal'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_deal';
    }

}
