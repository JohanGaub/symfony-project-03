<?php

namespace AppBundle\Form\Evolution;


use AppBundle\Repository\DictionaryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SearchTechnicalEvolution
 * @package AppBundle\Form\Evolution
 */
class SearchTechnicalEvolution extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('search', SearchType::class, [
                'label'         => false,
                'required'      => false,
                'attr'          => [
                    'placeholder' => 'Un titre ?'
                ]
            ])
            ->add('status', EntityType::class, [
                'class'         => 'AppBundle\Entity\Dictionary',
                'query_builder' => function (DictionaryRepository $repo) {
                    #Find all origin in dictionary
                    return $repo->getItemListByType('technical_evolution_status');
                },
                'label'         => false,
                'required'      => false,
                'multiple'      => false,
                'placeholder'   => 'Un status ?'
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => null
        ));
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'app_bundle_technicalEvolution';
    }

}