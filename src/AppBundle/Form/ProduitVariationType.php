<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProduitVariationType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('couleur', EntityType::class, array(
                    "required" => false,
                    "label" => "Couleur",
                    'class' => 'AppBundle:Couleur',
                    'choice_label' => function ($couleur) {
                        return $couleur->getNom();
                    }
                ))
                ->add('taille', EntityType::class, array(
                    "required" => false,
                    "label" => "Taille",
                    'class' => 'AppBundle:Taille',
                    'choice_label' => function ($taille) {
                        return $taille->getNom() . " - " . $taille->getCode();
                    }
                ))
                ->add('stock', NumberType::class, array(
                    "label" => "Stock",
                    "required" => true,
                    "attr" => array("placeholder" => "Stock")
                ))

        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ProduitVariation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_produitvariation';
    }

}
