<?php

namespace AppBundle\Form\Evolution;

use AppBundle\Entity\TechnicalEvolutionFilter;
use AppBundle\Repository\CategoryRepository;
use AppBundle\Repository\DictionaryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TechnicalEvolutionFilterType
 * @package AppBundle\Form\Evolution
 */
class TechnicalEvolutionFilterType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label'     => 'Titre',
                'mapped'    => false,
                'required'  => false,
            ])
            ->add('status', EntityType::class, [
                'class'         => 'AppBundle\Entity\Dictionary',
                'query_builder' => function (DictionaryRepository $repo) {
                    # Find all category_type for select list
                    return $repo->getItemListByType('status');
                },
                'label'         => 'Status',
                'placeholder'   => 'Sélectionnez votre status',
                'mapped'        => true,
                'required'      => false,
                'multiple'      => false,
            ])
            ->add('categoryType', EntityType::class, [
                'class'         => 'AppBundle\Entity\Dictionary',
                'query_builder' => function (DictionaryRepository $repo) {
                    # Find all category_type for select list
                    return $repo->getItemListByType('category_type');
                },
                'label'         => 'Type de catégorie',
                'placeholder'   => 'Sélectionnez votre type de catégorie',
                'mapped'        => true,
                'required'      => false,
                'multiple'      => false,
            ])
            ->add('category', ChoiceType::class, [
                'label'         => 'Catégorie',
                'placeholder'   => 'Séléctionnez votre catégorie',
                'required'      => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Filtrer'
            ]);

        $builder->get('categoryType')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->addCategoryTitleField($form->getParent(), $form->getData());
            }
        );
    }

    /**
     * @param FormInterface $form
     * @param $categoryType
     */
    private function addCategoryTitleField(FormInterface $form, $categoryType)
    {
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'category',
            EntityType::class,
            null,
            [
                'class'         => 'AppBundle\Entity\Category',
                'query_builder' => function(CategoryRepository $repo) use ($categoryType) {
                    # find category name by select type
                    return $repo->getCategoryByType($categoryType);
                },
                'label'         => 'Catégorie',
                'placeholder'   => 'Séléctionnez votre catégorie',
                'mapped'        => true,
                'required'      => false,
                'auto_initialize' => false,
            ]
        );
        $form->add($builder->getForm());
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TechnicalEvolutionFilter::class,
            'validation_groups' => false,
            'csrf_protection'   => false,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'app_bundle_filter_type';
    }
}