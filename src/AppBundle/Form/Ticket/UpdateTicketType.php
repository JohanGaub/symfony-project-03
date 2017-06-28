<?php

namespace AppBundle\Form\Ticket;

use AppBundle\Entity\Ticket;
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
            ->add('category', EntityType::class, [
                'class' => 'AppBundle\Entity\Category',
                'query_builder' => function (EntityRepository $entityRepository) {
                    return $entityRepository->createQueryBuilder('c')
                        ->orderBy('c.title', 'ASC');
                },
                'choice_label' => 'title',
                'label' => 'Catégorie',
                'required' => true,
            ])
            ->add('subject', TextType::class, ['label' => 'Sujet du ticket'])
            ->add('content', TextareaType::class, ['label' =>  'Explications'])
            ->add('origin', ChoiceType::class, [
                'label' => 'Origine',
                'choices'   => [
                    'Super adminitrateur' => 'Super adminitrateur',
                    'Administrateur' => 'Administrateur',
                    'Responsable projet' => 'Responsable projet',
                    'Technicien' => 'Technicien',
                    'Commercial' => 'Commercial',
                    'Client final' => 'Client final',
                ],
                'preferred_choices' => [
                    'Responsable projet' => 'Responsable projet',
                ],
                'required' => true,
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices'   => [
                    'Technique' => 'Technique',
                    'Commercial' => 'Commercial',
                    'Autre' => 'Autre',
                ],
                'required' => true,
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
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'En attente' => 'En attente',
                    'En cours' => 'En cours',
                    'Résolu' => 'Résolu',
                    'Fermé' => 'Fermé',
                ],
                'required' => true,
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
        return 'app_bundle_admin_ticket_type';
    }
}