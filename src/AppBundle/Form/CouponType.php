<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class CouponType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('libelle', TextType::class, array(
                    "label" => "Libellé",
                    "required" => true,
                    "attr" => array(
                        "placeholder" => "libellé"
                    )
                ))
                ->add('code', TextType::class, array(
                    "label" => "Code",
                    "required" => true,
                    "attr" => array(
                        "placeholder" => "Code"
                    )
                ))
                ->add('actif', CheckboxType::class, array(
                    "label" => "Activé",
                    "value" => true,
                    "required" => false
                ))
                ->add('freeShipping', CheckboxType::class, array(
                    "label" => "Livraison Gratuite",
                    "value" => true,
                    "required" => false
                ))
                ->add('pourcentage', CheckboxType::class, array(
                    "label" => "Sur pourcentage",
                    "value" => true,
                    "required" => false
                ))
                ->add('forAll', CheckboxType::class, array(
                    "label" => "Bénéficiaires",
                    "value" => true,
                    "required" => false
                ))
                ->add('valeur', NumberType::class, array(
                    "label" => "Réduction",
                    "attr" => array("placeholder" => "Réduction")
                ))
                ->add('dateDebut', DateTimeType::class, array(
                    'format' => 'dd/MM/yyyy H:mm',
                    'widget' => 'single_text',
                    'html5' => false,
                    "required" => false,
                    "label" => "Date Début"
                ))
                ->add('dateFin', DateTimeType::class, array(
                    'format' => 'dd/MM/yyyy H:mm',
                    'widget' => 'single_text',
                    'html5' => false,
                    "required" => false,
                    "label" => "Date Fin"
                ))
                ->add('montantMin', NumberType::class, array(
                    "label" => "Montant Minimum",
                    "required" => false,
                    "attr" => array("placeholder" => "Montant Minimum")
                ))
                ->add('montantMax', NumberType::class, array(
                    "label" => "Montant Maximum",
                    "required" => false,
                    "attr" => array("placeholder" => "Montant Maximum")
                ))
                ->add('clients', EntityType::class, array(
                    "required" => false,
                    "label" => "Clients",
                    'class' => 'AppBundle:Client',
                    'choice_label' => function ($client) {
                        return $client->getNom() . " " . $client->getPrenom();
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
            'data_class' => 'AppBundle\Entity\Coupon'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_coupon';
    }

}
