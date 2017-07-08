<?php

namespace AppBundle\Form\News;

use AppBundle\Entity\News;
use AppBundle\Repository\DictionaryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class NewsType
 * @package AppBundle\Form\Evolution
 */
class NewsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isVisible', CheckboxType::class, [
                'label'     => 'Cocher cette case rendra la news visible sur le tableau de bord',
                'required'  => false
            ])
            ->add('type', EntityType::class, [
                'class'         => 'AppBundle\Entity\Dictionary',
                'query_builder' => function (DictionaryRepository $repo) {
                    # Find all category_type for select list
                    return $repo->getItemListByType('category_type');
                },
                'label'         => 'Type',
                'placeholder'   => 'Sélectionnez votre type de news',
                'mapped'        => false,
                'required'      => true,
                'multiple'      => false,
            ])
            ->add('title', TextType::class, [
                'label' => 'Titre'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ]);

    }
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => News::class
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'app_bundle';
    }
}