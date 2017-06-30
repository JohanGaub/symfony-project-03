<?php

namespace AppBundle\Form\Evolution;

use AppBundle\Repository\CategoryRepository;
use AppBundle\Entity\TechnicalEvolution;
use AppBundle\Repository\DictionaryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TechnicalEvolutionType
 * @package AppBundle\Form\Evolution
 */
class TechnicalEvolutionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Nom de l\'évolution'])
            ->add('sum_up', TextareaType::class, ['label' => 'Résumé'])
            ->add('content', TextareaType::class, ['label' => 'Contenu'])
            ->add('reason', TextType::class, [
                'label'         => 'Raison',
            ])
            ->add('origin', EntityType::class, [
                'class'         => 'AppBundle\Entity\Dictionary',
                'query_builder' => function (DictionaryRepository $repo) {
                    #Find all status in dictionary
                    return $repo->getEvolutionOriginTypeList();
                },
                'label'         => 'Origine de la demande',
                'placeholder'   => 'Qui est à la base de cette évolution ?',
                'multiple'      => false,
            ])
            ->add('expectedDelay', DateType::class, [
                'label'         => 'Délais souhaité',
                'format'        => 'dd MM yyyy'
            ])
            ->add('product', EntityType::class, [
                'class'         => 'AppBundle\Entity\Product',
                'choice_label'  => 'name',
                'label'         => 'Produit',
                'placeholder'   => 'Séléctionnez votre produit',
                'multiple'      => false,
                'required'      => 'true',
            ])
            ->add('category_type', EntityType::class, [
                'class'         => 'AppBundle\Entity\Dictionary',
                'query_builder' => function (DictionaryRepository $repo) {
                    # Find all category_type for select list
                    return $repo->getCategoryTypeList();
                },
                'label'         => 'Type de catégorie',
                'placeholder'   => 'Sélectionnez votre type de catégorie',
                'mapped'        => false,
                'required'      => true,
                'multiple'      => false,
            ])
            ->add('category', ChoiceType::class, [
                'label'         => 'Catégorie',
                'placeholder'   => 'Séléctionnez votre catégorie',
            ])
            ->add('submit', SubmitType::class, [
                'label' =>  'Soumettre la demande'
            ])
        ;

        $builder->get('category_type')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $categoryType = $event->getForm()->getData();
                $form = $event->getForm();
                $this->addCategoryNameField($form->getParent(), $categoryType);
            }
        );
    }

    /**
     * Add Category name field to form
     *
     * @param FormInterface $form
     * @param $categoryType
     * @internal param Dictionary $dictionary
     */
    private function addCategoryNameField(FormInterface $form, $categoryType)
    {
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'category',
            EntityType::class,
            null,
            [
                'class'         => 'AppBundle\Entity\Category',
                'query_builder' => function(CategoryRepository $repo) use ($categoryType) {
                    # find category name by select type
                    return $repo->getCategoryNameList($categoryType);
                },
                'label'         => 'Catégorie',
                'placeholder'   => 'Séléctionnez votre catégorie',
                'mapped'        => true,
                'required'      => true,
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
        $resolver->setDefaults(array(
            'data_class' => TechnicalEvolution::class
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'app_bundle_technicalEvolution';
    }

}