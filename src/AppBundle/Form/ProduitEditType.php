<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Form\ImageType;
use AppBundle\Form\ProduitVariationType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class ProduitEditType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('titre', TextType::class, array(
                    "label" => "Titre",
                    "required" => true,
                    "attr" => array("placeholder" => "Titre")
                ))
                ->add('description', TextareaType::class, array(
                    "label" => "Déscription",
                    "required" => false
                ))
                ->add('prix', NumberType::class, array(
                    "label" => "Prix",
                    "required" => true,
                    "attr" => array("placeholder" => "Prix")
                ))
                ->add('stock', NumberType::class, array(
                    "label" => "Stock",
                    "required" => true,
                    "attr" => array("placeholder" => "Stock")
                ))
                ->add('occasion', CheckboxType::class, array(
                    "label" => "Occasion",
                    "value" => true,
                    "required" => false
                ))
                ->add('enSolde', CheckboxType::class, array(
                    "label" => "En solde",
                    "value" => true,
                    "required" => false
                ))
                ->add('isNew', CheckboxType::class, array(
                    "label" => "Nouveau",
                    "value" => true,
                    "required" => false
                ))
                ->add('dateEndNew', DateTimeType::class, array(
                    'time_widget' => 'single_text',
                    'date_widget' => 'single_text',
                    'html5' => true,
                    "required" => false,
                    "label" => "Date fin nouveau"
                ))
                ->add('isBestProduct', CheckboxType::class, array(
                    "label" => "Meilleur produit",
                    "value" => true,
                    "required" => false
                ))
                ->add('categories', EntityType::class, array(
                    "required" => false,
                    "label" => "Catégories",
                    'class' => 'AppBundle:Categorie',
                    'choice_label' => function ($categorie) {
                        return $categorie->getLibelle();
                    },
                    "multiple" => true
                ))
                ->add('marque', EntityType::class, array(
                    "required" => false,
                    "label" => "Marque",
                    'class' => 'AppBundle:Marque',
                    'choice_label' => function ($marque) {
                        return $marque->getNom();
                    }
                ))
                ->add('genres', EntityType::class, array(
                    "required" => false,
                    "label" => "Genres",
                    'class' => 'AppBundle:Genre',
                    'choice_label' => function ($genre) {
                        return $genre->getLibelle();
                    },
                    "multiple" => true
                ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Produit'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_produit';
    }

}
