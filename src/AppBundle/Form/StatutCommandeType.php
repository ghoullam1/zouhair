<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class StatutCommandeType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('libelle', TextType::class, array(
                    "label" => "Libellé",
                    "required" => true,
                    "attr" => array("placeholder" => "libellé")))
                ->add('estPaye', CheckboxType::class, array(
                    "label" => "Est payé",
                    "value" => true,
                    "required" => false
                ))
                ->add('estLivre', CheckboxType::class, array(
                    "label" => "Est livré",
                    "value" => true,
                    "required" => false
                ))
                ->add('sendMail', CheckboxType::class, array(
                    "label" => "Envoyer un email",
                    "value" => true,
                    "required" => false
                ))
                ->add('template', TextareaType::class, array(
                    "label" => "Template",
                    "required" => false,
                ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\StatutCommande'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_statutcommande';
    }

}
