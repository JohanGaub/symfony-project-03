<?php

namespace AppBundle\Form\Ticket;

use AppBundle\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditTicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('emergency', ChoiceType::class, [
                'label' => 'Urgence',
                'choices'  => [
                    'Normal' => 'normal',
                    'Haute' => 'high',
                ],
                'required' => true,
            ])
            ->add('Status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'En attente' => 'waiting',
                    'En cours' => 'in_progress',
                    'Résolu' => 'resolved',
                    'Fermé' => 'closed',
                    'Archivé' => 'archived',
                ],
                'required' => true,
            ])

            ->add('submit', SubmitType::class)
        ;
    }

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
