<?php

namespace AppBundle\Form\Ticket;

use AppBundle\Entity\Ticket;
use AppBundle\Repository\DictionaryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

/**
 * Class EditTicketType
 * @package AppBundle\Form\Ticket
 */
class EditTicketType extends AbstractType
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
            },900) // To disable Symfony to check if uploaded file is too large or if non-existing fields were submitted.
            ->add('emergency', ChoiceType::class, [
                'label'         => 'Urgence',
                'choices'       => [
                    'Normale'   => 'Normale',
                    'Haute'     => 'Haute',
                ],
                'required' => true,
                'expanded' => true,
                'multiple' => false,
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
                }
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

    public function getBlockPrefix()
    {
        return 'app_bundle_edit_ticket_type';
    }
}
