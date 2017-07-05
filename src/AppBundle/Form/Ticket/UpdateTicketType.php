<?php

namespace AppBundle\Form\Ticket;

use AppBundle\Entity\Ticket;
use AppBundle\Repository\CategoryRepository;
use AppBundle\Repository\DictionaryRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * Class UpdateTicketType
 * @package AppBundle\Form\Ticket
 */
class UpdateTicketType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event){
                $event->stopPropagation();
            },900)
            ->add('product', EntityType::class, [
                'class' => 'AppBundle\Entity\Product',
                'query_builder' => function (EntityRepository $entityRepository) {
                    return $entityRepository->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
                'choice_label' => 'name',
                'label' => 'Produit',
                'required' => true,
            ])
            /*            ->add('category_type', EntityType::class, [
                            'class' => 'AppBundle\Entity\Dictionary',
                            'query_builder' => function (DictionaryRepository $dictionaryRepository) {
                                return $dictionaryRepository->getItemListByType('category_type');
                            },
                            'choice_label' => 'type',
                            'label' => 'Type de catégorie',
                            'required' => true,
                        ])*/

            /*    ->add('category_type', EntityType::class, [
                    'label'         => 'Type de catégorie',
                    //'placeholder' => 'Sélectionnez le type de catégorie',
                    'class'             => 'AppBundle\Entity\Dictionary',
                    'required'      => true,
                    'mapped'        => true,
                    'query_builder' => function(DictionaryRepository $dictionaryRepository) {
                        return $dictionaryRepository->getItemListByType('category_type');
                    },
                ])

                /*           ->add('category_title', EntityType::class, [
                               'label'         => 'Titre de catégorie',
                               'class'         => 'AppBundle\Entity\Category',
                               'required'      => true,
                               'mapped'        => true,
                               'query_builder' => function (CategoryRepository $categoryRepository) {
                                   return $categoryRepository->getCategoryByType('category_title');
                               },
                               //'choice_label' => 'title',
                           ])*/

            ->add('subject', TextType::class, ['label' => 'Sujet du ticket'])
            ->add('content', TextareaType::class, ['label' =>  'Explications'])
            ->add('origin', EntityType::class, [
                'label' => 'Origine',
                'class' => 'AppBundle\Entity\Dictionary',
                'required' => true,
                'query_builder' => function(DictionaryRepository $dictionaryRepository) {
                    return $dictionaryRepository->getItemListByType('origin');
                },
            ])
            ->add('type', EntityType::class, [
                'label' => 'Type',
                'class' => 'AppBundle\Entity\Dictionary',
                'required' => true,
                'query_builder' => function(DictionaryRepository $dictionaryRepository) {
                    return $dictionaryRepository->getItemListByType('ticket_type');
                }
            ])
            ->add('emergency', ChoiceType::class, [
                'label' => 'Urgence',
                'choices'  => [
                    'Normale' => 'Normale',
                    'Haute' => 'Haute',
                ],
                'required' => true,
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('status', EntityType::class, [
                'label' => 'Statut',
                'class' => 'AppBundle\Entity\Dictionary',
                'required' => true,
                'query_builder' => function(DictionaryRepository $dictionaryRepository) {
                    return $dictionaryRepository->getItemListByType('status');
                }
            ])
            ->add('isArchive', CheckboxType::class, [
                'label' => 'Archivage',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, ['label' => 'Valider'])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
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
