<?php
/**
 * Created by PhpStorm.
 * User: topikana
 * Date: 17/07/17
 * Time: 11:06
 */

namespace AppBundle\Form\Account;


use AppBundle\Entity\User;
use AppBundle\Form\User\CompanyType;
use AppBundle\Form\User\User_profileType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AccountModifyRespType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, ['label' => 'Email/Login'])
            ->add('userProfile', User_profileType::class, array(
                'label' => false,
            ))
            ->add('company', CompanyType::class, array(
                'label' => false,
            ))
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer'
            ]);
    }

    public
    function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class,
        ));
    }

}