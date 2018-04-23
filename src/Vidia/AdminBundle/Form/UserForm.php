<?php

namespace Vidia\AdminBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username', TextType::class, ['attr' => ['class' => 'form-control border-input']])
        ->add('fullName', TextType::class, ['attr' => ['class' => 'form-control border-input']])
        ->add('email', EmailType::class, ['attr' => ['class' => 'form-control border-input']])
        ->add('phone', TextType::class, ['attr' => ['class' => 'form-control border-input']])
        ->add('gender', ChoiceType::class, ['attr' => ['class' => 'form-control border-input'],
            'choices' => [
                 'male' => 'Male',
                 'female' => 'Female',
                ], ])
        ->add('language', EntityType::class, [
            'class' => 'AppBundle:Language', 'choice_label' => 'name',
            'attr' => ['class' => 'form-control border-input'], ])
        ->add('dateOfBirth', DateType::class, [
            'widget' => 'single_text',
            'html5' => 'false',
            'attr' => ['class' => 'form-control border-input datepicker'], ])
        ->add('role', ChoiceType::class, ['empty_data' => 'ROLE_USER', 'attr' => ['class' => 'form-control border-input'],
            'choices' => [
                    'ROLE_SUPER_ADMIN' => 'Super admin',
                    'ROLE_ADMIN' => 'Admin',
                    'ROLE_USER' => 'User',
                ], ])
        ->add('save', SubmitType::class, ['label' => 'Update Profile', 'attr' => ['class' => 'btn btn-info btn-fill btn-wd update-profile-btn']]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'allow_extra_fields' => true,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'adminbundle_user';
    }
}
