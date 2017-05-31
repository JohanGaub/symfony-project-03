<?php

namespace AppBundle\Form;

use AppBundle\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $builder
        ->add('subject')
        ->add('content')
        ->add('origin', ChoiceType::class, [])
        ->add('type')
        ->add('emergency')
        ->add('status')
        ->add('upload', null)
        ->add('creationDate')
        ->add('uploadDate', null)
        ->add('endDate', null)
        ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
$resolver->setdefaults(['data_class' => Ticket::class,]);
    }

    public function getBlockPrefix()
    {
        return 'app_bundle_ticket_type';
    }
}
