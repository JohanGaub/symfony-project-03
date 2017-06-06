<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\Ticket;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject', TextType::class, ['label' => 'Sujet du ticket'])
            ->add('content', TextareaType::class, ['label' =>  'Contenu, explications'])
            /*     ->add('origin', EntityType::class, [
                     'class' => 'AppBundle\Entity\Dictionary',
                     'query_builder' => function (DictionaryRepository $dictionaryRepository) {
                         return $dictionaryRepository->createQueryBuilder('u')
                             ->orderBy('u'.'type', 'ASC');
                     },
                     'choice_label'  => 'type',
                     'choice_attr'   => 'value',
                     'choice_value'  => 'value',
                     'multiple'      =>true,
                 ]) */






            ->add('origin', ChoiceType::class, [
                'choices'   => [
                    'Super adminitrateur' => 'super_administrator',
                    'Administrateur' => 'administrator',
                    'Responsable projet' => 'project_responsible',
                    'Technicien' => 'technician',
                    'Commercial' => 'commercial',
                    'Client final' => 'final_client',
                ],
                'multiple'  => false,
                'placeholder' => 'Sélectionner une origin',
                'required' => false,
            ])


            ->add('type', ChoiceType::class, [
                'choices'   => [
                    'Technique' => 'technical',
                    'Commercial' => 'commercial',
                    'Autre' => 'other',
                ],
                'multiple'  => false,
                'placeholder' => 'Sélectionner un type',
                'required' => true,
            ])
            ->add('emergency', ChoiceType::class, [
                'choices'  => [
                    'Haute',
                    'Moyenne',
                    'Basse',
                ],

            ])
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'En attente',
                    'En cours',
                    'Résolu',
                    'Fermé',
                    'Archivé',
                ],
            ])
            ->add('upload', FileType::class)
            ->add('creationDate', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/mm/yyyy',
            ])
            ->add('updateDate', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/mm/yyyy',
            ])
            ->add('endDate', DateType::class, [
                'widget' => 'single_text',
                'format' => 'dd/mm/yyyy',
            ])
            ->add('save', SubmitType::class)
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
        return 'app_bundle_ticket_type';
    }
}
