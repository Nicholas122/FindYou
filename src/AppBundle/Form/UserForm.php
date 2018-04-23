<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
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
            ->add('username')
            ->add('fullName')
            ->add('email')
            ->add('phone')
            ->add('gender')
            ->add('language')
            ->add('photo')
            ->add('dateOfBirth', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime',
                'required' => 'false',
                'format' => DateTimeType::HTML5_FORMAT, ])
            ->add('role', null, ['empty_data' => 'ROLE_USER'])
            ->add('password', null, array(
                'property_path' => 'plainPassword',
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'allow_extra_fields' => true,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }
}
