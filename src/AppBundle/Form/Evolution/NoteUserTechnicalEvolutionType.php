<?php

namespace AppBundle\Form\Evolution;

use AppBundle\Entity\UserTechnicalEvolution;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

/**
 * Class NoteUserTechnicalEvolutionType
 * @package AppBundle\Form\Evolution
 */
class NoteUserTechnicalEvolutionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
/*            ->add('note', HiddenType::class, [
               'attr' => [
                   'min' => 1,
                   'max' => 10
               ]
            ])*/
            ->add('note', ChoiceType::class, [
                'choices' => [ '1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '10' => 10],
                'expanded' => true,
                'multiple' => false,
                //'required' => true,
                'attr' => ['class' => 'star-link'],
                'label_attr' => ['class' => 'full label-star-link']

            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UserTechnicalEvolution::class
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'app_bundle_note_userTechnicalEvolution';
    }


}