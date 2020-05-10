<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class TemplateType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('libelle', TextType::class, array(
                    "label" => "Libellé",
                    "required" => true,
                    "attr" => array("placeholder" => "libellé"))
                )
                ->add('objet', TextType::class, array(
                    "label" => "Objet",
                    "required" => true,
                    "attr" => array("placeholder" => "Objet"))
                )
                ->add('contenu', TextareaType::class, array(
                    "label" => "Contenu",
                    "required" => true,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Template'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_template';
    }

}
