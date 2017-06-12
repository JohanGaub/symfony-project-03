<?php

namespace AppBundle\Form;

use AppBundle\Entity\Ticket;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditTicketType extends AbstractType
{
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
                'label' => 'produit',
            ])
            ->add('category', EntityType::class, [
                'class' => 'AppBundle\Entity\Category',
                'query_builder' => function (EntityRepository $entityRepository) {
                    return $entityRepository->CreateQueryBuilder('c')
                        ->orderBy('c.title');
                },
                'choice_label' => 'title',
                'label' => 'Catégorie',
            ])
            ->add('subject', TextType::class, [
                'label' => 'Sujet du ticket'
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Explication'
            ])
            ->add('origin', ChoiceType::class, [
                'label' => 'origine',
                'choices' => [
                    'Super adminitrateur' => 'super_administrator',
                    'Administrateur' => 'administrator',
                    'Responsable projet' => 'project_responsible',
                    'Technicien' => 'technician',
                    'Commercial' => 'commercial',
                    'Client final' => 'final_client',
                ],
                'preferred_choices' => 'project_responsible',
                'required' => true,
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'Technique' => 'technical',
                    'Commercial' => 'commercial',
                    'Autre' => 'other',
                ],
                'required' => true,
            ])
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
            ->add('upload', FileType::class, [
                'label' => 'Fichier à uploader',
                'required' => false,
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
