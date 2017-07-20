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
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
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
                'class'         => 'AppBundle\Entity\Product',
                'query_builder' => function (EntityRepository $entityRepository) {
                    return $entityRepository->createQueryBuilder('p')
                        ->orderBy('p.name', 'ASC');
                },
                'choice_label'  => 'name',
                'label'         => 'Produit',
                'required'      => true,
            ])
            ->add('category_type', EntityType::class, [
                'label'         => 'Type de catégorie',
                'class'         => 'AppBundle\Entity\Dictionary',
                'query_builder' => function (DictionaryRepository $dictionaryRepository) {
                    return $dictionaryRepository->getItemListByType('category_type');
                },
                'required'      => true,
                'multiple'      => false,
                'mapped'        => false,
            ])
            ->add('category', ChoiceType::class, [
                'label'         => 'Titre de catégorie',
                'placeholder'   => 'Sélectionnez le titre de catégorie',
                'required'      => true,
                'multiple'      => false,
            ])
            ->add('subject', TextType::class, ['label' => 'Sujet du ticket'])
            ->add('content', TextareaType::class, [
                'label' =>  'Explications',
                //'attr' => ['class' => 'ticket-explanation-size'],
            ])
            ->add('origin', EntityType::class, [
                'label'         => 'Origine',
                'class'         => 'AppBundle\Entity\Dictionary',
                'required'      => true,
                'query_builder' => function(DictionaryRepository $dictionaryRepository) {
                    return $dictionaryRepository->getItemListByType('origin');
                },
            ])
            ->add('ticket_type', EntityType::class, [
                'label'         => 'Type',
                'class'         => 'AppBundle\Entity\Dictionary',
                'required'      => true,
                'query_builder' => function(DictionaryRepository $dictionaryRepository) {
                    return $dictionaryRepository->getItemListByType('ticket_type');
                }
            ])
            ->add('emergency', ChoiceType::class, [
                'label'         => 'Urgence',
                'choices'       => [
                    'Normale'   => 'Normale',
                    'Haute'     => 'Haute',
                ],
                'required'      => true,
                'expanded'      => true,
                'multiple'      => false,
                'attr'  => [
                    'class'  => 'checkbox-inline',
                ],

            ])
            ->add('status', EntityType::class, [
                'label'         => 'Statut',
                'class'         => 'AppBundle\Entity\Dictionary',
                'required'      => true,
                'query_builder' => function(DictionaryRepository $dictionaryRepository) {
                    return $dictionaryRepository->getItemListByType('status');
                },
                //'mapped'        => false,
                //'multiple'      => false,
            ])
            ->add('isArchive', CheckboxType::class, [
                'label'         => 'Archivage',
                'required'      => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr'  => [
                    'class'         => 'btn btn-model-small',
                    'aria-hidden'   => 'true',
                ],
            ])
        ;
        // To listen to the "category_type" field
        $builder->get('category_type')->addEventListener(
        // Get the key from the constant from class FormEvents
            FormEvents::POST_SUBMIT,
            // Function callback executed when the event is happening with Instance $event
            function (FormEvent $event) {
                // Create the form
                $form = $event->getForm();
                $this->addCategoryTitleField($form->getParent(), $form->getData());
            }
        );
    }

    /**
     * @param FormInterface $form
     * @param $searchType
     */
    public function addCategoryTitleField(FormInterface $form, $searchType) {
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'category',
            EntityType::class,
            null,
            [
                'label'             => 'Titre de catégorie',
                'class'             => 'AppBundle\Entity\Category',
                //'placeholder'       => 'Sélectionnez le titre de catégorie',
                'mapped'            => true,
                'required'          => true,
                'auto_initialize'   => false,
                'query_builder'     => function (CategoryRepository $categoryRepository) use ($searchType) {
                    return $categoryRepository->getCategoryByType($searchType);
                }
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
