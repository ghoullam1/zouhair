<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Form\ImageType;

class CategorieType extends AbstractType {

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('libelle', TextType::class, array("label" => "Libellé", "required" => true, "attr" => array("placeholder" => "libellé")))
                ->add('genres', EntityType::class, array(
                    "required" => false,
                    "label" => "Genres",
                    'class' => 'AppBundle:Genre',
                    'choice_label' => function ($genre) {
                        return $genre->getLibelle();
                    },
                    "multiple" => true
                ))
                ->add('image', ImageType::class, array("required" => false))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Categorie'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix() {
        return 'appbundle_categorie';
    }

}
