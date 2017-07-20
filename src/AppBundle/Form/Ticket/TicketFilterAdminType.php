<?php

namespace AppBundle\Form\Ticket;

use AppBundle\Entity\TicketFilter;
use AppBundle\Repository\CategoryRepository;
use AppBundle\Repository\DictionaryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TicketFilterAdminType
 * @package AppBundle\Form\Ticket
 */
class TicketFilterAdminType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', TextType::class, [
                'label' => "N° de ticket",
                'required' => false,
            ])
            ->add('company', TextType::class, [
                'label' => 'Société',
                'required' => false,
            ])

            ->add('emergency', ChoiceType::class, [
                'label' => 'Urgence',
                'required' => false,
                'expanded' => false,
                'multiple' => false,
                'choices'  => [
                    'Normale'   => 'Normale',
                    'Haute'     => 'Haute',
                ],
            ])
            ->add('subject', TextType::class, [
                'label' => 'Sujet du ticket',
                'required' => false,
            ])
            ->add('status', EntityType::class, [
                'label'         => 'Statut',
                'class'         => 'AppBundle\Entity\Dictionary',
                'required'      => false,
                'expanded'      => false,
                'multiple'      => false,
                'mapped'        => true,
                'query_builder' => function(DictionaryRepository $dictionaryRepository) {
                    return $dictionaryRepository->getItemListByType('status');
                }
            ])
            ->add('origin', EntityType::class, [
                           'class'         => 'AppBundle\Entity\Dictionary',
                           'query_builder' => function (DictionaryRepository $repo) {
                               #Find all origin in dictionary
                               return $repo->getItemListByType('origin');
                           },
                           'label'         => 'Origine',
                           'multiple'      => false,
                           'mapped'        => true,
                           'required'      => false,
                       ])
            ->add('creationDate', DateType::class, [
                'label'     => 'Date de création',
                'widget'    => 'single_text',
                'required' => false,
                'html5' => false,
                'format'        => 'dd/MM/yyyy',
                'attr'      => [
                    //'placeholder'   => 'jj/mm/aaaa',
                    'class'         => 'datepicker1',
                ],
            ])
            ->add('endDate', DateType::class, [
                'label'     => 'Date de clôture',
                'widget'    => 'single_text',
                'required'  => false,
                'html5' => false,
                'format'        => 'dd/MM/yyyy',
                'attr'      => [
                    'class'         => 'datepicker2',
                ],
            ])
            ->add('isArchive', ChoiceType::class, [
                'label' => 'Tickets archivés',
                'required' => false,
                'expanded' => false,
                'multiple' => false,
                'choices' => [
                    'Archivé' => '1',
                    'Non archivé' => '0',
                ],
            ]);

        $builder
            ->add('submit', SubmitType::class, [
                'label' => 'Filtrer',
                'attr'  => [
                    'class'         => 'btn btn-model-small',
                    'aria-hidden'   => 'true',
                ]
            ]);
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
                'placeholder'   => 'Sélectionnez un titre de catégorie',
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
        $resolver->setdefaults([
            'data_class'        => TicketFilter::class,
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
