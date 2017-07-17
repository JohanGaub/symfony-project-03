<?php

namespace AppBundle\Form;

use AppBundle\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchTicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', null,
                [
                    'required' => false,
                    'empty_data' => 'En cours',
                    'placeholder' => 'Exemple : En cours',
                    'label' => 'Recherche. valeur par défaut = "En cours"'

                ])
            ->add('submit', SubmitType::class, ['label' => 'Valider'])


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setdefaults([
            'data_class' => Ticket::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_search_ticket_type';
    }
}
