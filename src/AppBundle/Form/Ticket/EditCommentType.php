<?php

namespace AppBundle\Form\Ticket;

use AppBundle\Entity\Comment;
use AppBundle\Repository\CommentRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EditCommentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Comment', EntityType::class, [
                'label' => 'Commentaire',
                'class' => 'AppBundle\Entity\Comment',
                'required' => true,
                'query_builder' => function(CommentRepository $commentRepository) {
                    return $commentRepository->getComment('ticket');
                }
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr'  => [
                    'class'         => 'btn btn-model-small',
                    'aria-hidden'   => 'true',
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comment::class,
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
