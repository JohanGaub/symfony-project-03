<?php

namespace AppBundle\Form\Ticket;

use AppBundle\Entity\Category;
use AppBundle\Entity\Dictionary;
use AppBundle\Entity\Ticket;
use AppBundle\Repository\CategoryRepository;
use AppBundle\Repository\DictionaryRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class AddTicketType
 * @package AppBundle\Form\Ticket
 */
class AddTicketType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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

            ->add('category_type', EntityType::class, [
                'label' => 'Type de catégorie',
                'placeholder' => 'Sélectionnez le type de catégorie',
                'class' => 'AppBundle\Entity\Dictionary',
                'required' => true,
                'mapped' => false,
                'query_builder' => function(DictionaryRepository $dictionaryRepository) {
                    return $dictionaryRepository->getItemListByType('category_type');
                },
            ])
            ->add('category', ChoiceType::class, [
                'label' => 'Titre de catégorie',
                'placeholder' => 'Sélectionnez le titre de catégorie',
                'required' => true,
            ])
            ->add('subject', TextType::class, ['label' => 'Sujet du ticket'])
            ->add('content', TextareaType::class, ['label' =>  'Explications'])
            ->add('origin', EntityType::class, [
                'label' => 'Origine',
                'class' => 'AppBundle\Entity\Dictionary',
                'required' => true,
                'query_builder' => function(DictionaryRepository $dictionaryRepository) {
                    return $dictionaryRepository->getItemListByType('origin');
                },
                //'preferred_choices' => ,
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
                'data' => 'Normale',
            ])
            ->add('upload', FileType::class, [
                'label' => 'Fichier à uploader',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, ['label' => 'Valider'])
        ;
        // To listen to the "category_type" field
        $builder->get('category_type')->addEventListener(
        // Get the key from the constant from class FormEvents
            FormEvents::POST_SUBMIT,
            // Function callback executed when the event is happening with Instance $event
            function (FormEvent $event) {
                // Create the form
                $form = $event->getForm();
                $this->addcategoryTitleField($form->getParent(), $form->getData());
            }
        );
    }

    /**
     * @param FormInterface $form
     * @param Dictionary $searchType
     * @internal param Dictionary $categoryType
     */
    public function addcategoryTitleField(FormInterface $form, $searchType) {
        $builder = $form->getConfig()->getFormFactory()->createNamedBuilder(
            'category',
            EntityType::class,
            null,
            [
                'label' => 'Titre de catégorie',
                'class' => 'AppBundle\Entity\Category',
                'placeholder' => 'Sélectionnez le titre de catégorie',
                'mapped' => true,
                'required' => true,
                'auto_initialize' => false,
                'query_builder' => function (CategoryRepository $categoryRepository) use ($searchType) {
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
        $resolver->setdefaults([
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
