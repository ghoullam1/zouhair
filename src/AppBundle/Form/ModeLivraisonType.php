<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ModeLivraisonType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('libelle', TextType::class, array(
                    "label" => "Libellé",
                    "required" => true,
                    "attr" => array("placeholder" => "libellé")
                ))
                ->add('fraisLivraison', NumberType::class, array(
                    "label" => "Frais",
                    "required" => true,
                    "attr" => array("placeholder" => "Frais de livraison")
                ))
                ->add('ville', EntityType::class, array(
                    "required" => true,
                    "label" => "Ville",
                    'class' => 'AppBundle:Ville',
                    'choice_label' => function ($ville) {
                        return $ville->getNom();
                    }
                ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\ModeLivraison'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_modelivraison';
    }

}
