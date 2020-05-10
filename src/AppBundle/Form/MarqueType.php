<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Form\ImageType;

class MarqueType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('nom', TextType::class, array("label" => "Nom", "required" => true, "attr" => array("placeholder" => "Nom")))
                ->add('categories', EntityType::class, array(
                    "required" => false,
                    "label" => "Catégories",
                    'class' => 'AppBundle:Categorie',
                    'choice_label' => function ($ctegorie) {
                        return $ctegorie->getLibelle();
                    },
                    "multiple" => true
                ))
                ->add('image', ImageType::class, array("required" => true))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Marque'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_marque';
    }

}
