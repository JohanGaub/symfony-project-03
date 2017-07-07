<?php

namespace AppBundle\Form\Category;

use AppBundle\Entity\Category;
use AppBundle\Repository\DictionaryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CategoryType
 * @package AppBundle\Form\Dictionary
 */
class CategoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('type', EntityType::class, [
                'class'         => 'AppBundle\Entity\Dictionary',
                'query_builder' => function (DictionaryRepository $repo) {
                    return $repo->getItemListByType('category_type');
                },
                'label'         => 'Type de votre catégorie',
                'multiple'      => false,
                'required'      => true,
                'placeholder'   => 'Sélectionnez votre type'
            ])
            ->add('description', TextareaType::class, [
                'label'     => 'Desscription'
            ])
            ->add('submit', SubmitType::class, [
                'label'     => 'Enregistrer'
            ])
        ;

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Category::class
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'app_bundle_category';
    }

}