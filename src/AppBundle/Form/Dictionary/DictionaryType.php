<?php

namespace AppBundle\Form\Dictionary;

use AppBundle\Entity\Dictionary;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DictionaryType
 * @package AppBundle\Form\Evolution
 */
class DictionaryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value', TextType::class, [
                'label' => false,
                'attr' => array(
                    'placeholder' => 'Appuyez sur ENTRER',
                )
            ]);

    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Dictionary::class
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