<?php

namespace AppBundle\Form\Evolution;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\TechnicalEvolution;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

/**
 * Class TechnicalEvolutionType
 * @package AppBundle\Form
 */
class TechnicalEvolutionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, ['label' => 'Nom de l\'évolution'])
            ->add('category_type', EntityType::class, [
                'class'             => 'AppBundle\Entity\Category',
                'choice_label'      => function ($category) { return $category->getType(); },
                'placeholder'       => 'Sélectionnez votre type de catégorie',
                'mapped'            => false,
                'label'             => 'Type de categorie',
            ])
            ->add('submit', SubmitType::class, ['label' => 'Enregistrer']);

        $builder->get('category_type')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form           = $event->getForm()->getParent();
                $category_type  = $event->getForm()->getData()->getType();

                $formOptions = [
                    'class'         => 'AppBundle\Entity\Category',
                    'choice_label'  => $form->getData()->getTitle(),
                    'query_builder' => function(EntityRepository $er) use ($category_type){
                        return $er->createQueryBuilder('c')
                            ->select('c.title')
                            ->where('c.type = :category_type')
                            ->setParameter('category_type', $category_type);
                    },
                    'placeholder'   => 'Sélectionnez votre type de catégorie',
                    'label'         => 'Nom de la categorie',
                    'mapped'        => false,
                ];

                $form->add('category_name', EntityType::class, $formOptions);
            }
        );


    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => TechnicalEvolution::class
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'app_bundle_technicalEvolution_type';
    }

}