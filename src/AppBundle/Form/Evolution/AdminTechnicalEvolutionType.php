<?php

namespace AppBundle\Form\Evolution;

use AppBundle\Entity\TechnicalEvolution;
use AppBundle\Repository\CategoryRepository;
use AppBundle\Repository\DictionaryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
 * Class AdminTechnicalEvolutionType
 * @package AppBundle\Form\Evolution
 */
class AdminTechnicalEvolutionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('isArchivate', CheckboxType::class, [
                'label'         => 'Archivage de l\'évolution (cocher cette case archivera l\'évolution',
                'required'      => false
            ])
            ->add('status', EntityType::class, [
                'class'         => 'AppBundle\Entity\Dictionary',
                'query_builder' => function (DictionaryRepository $repo) {
                    return $repo->getItemListByType('evolution_status');
                },
                'label'         => 'Status de la demande',
                'placeholder'   => 'Status cette évolution',
                'multiple'      => false
            ])
            ->add('title', TextType::class, [
                'label' => 'Nom de l\'évolution'
            ])
            ->add('sum_up', TextareaType::class, [
                'label' => 'Résumé'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu'
            ])
            ->add('reason', TextType::class, [
                'label'         => 'Raison',
            ])
            ->add('origin', EntityType::class, [
                'class'         => 'AppBundle\Entity\Dictionary',
                'query_builder' => function (DictionaryRepository $repo) {
                    #Find all origin in dictionary
                    return $repo->getItemListByType('origin');
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
                    return $repo->getItemListByType('category_type');
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
                'label' =>  'Enregistrer'
            ])
        ;

        $builder->get('category_type')->addEventListener(
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
        $resolver->setDefaults([
            'data_class' => TechnicalEvolution::class
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