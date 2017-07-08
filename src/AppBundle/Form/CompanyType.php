<?php
namespace AppBundle\Form;
use AppBundle\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Created by PhpStorm.
 * User: topikana
 * Date: 24/05/17
 * Time: 16:04
 */
class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['label' => 'Nom de votre Socièté'])
            ->add('address', TextType::class, ['label' => 'Adresse'])
            ->add('town', TextType::class, ['label' => 'Ville'])
            ->add('postCode', TextType::class, ['label' => 'Code postale'])
            ->add('phone', IntegerType::class,  ['label' => 'Téléphone fixe'])
            ->add('siret', IntegerType::class,  ['label' => 'N° Siret'])
            ->add('email', TextType::class,  ['label' => 'Email de votre socièté']);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Company::class,
        ));
    }
}