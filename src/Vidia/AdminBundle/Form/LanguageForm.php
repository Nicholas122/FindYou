<?php

namespace Vidia\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LanguageForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', TextType::class, [
            'attr' => ['class' => 'form-control border-input', 'placeholder' => 'Name'],
            'label' => 'Name', ])
        ->add('locale', TextType::class, [
            'attr' => ['class' => 'form-control border-input', 'placeholder' => 'Locale'],
            'label' => 'Locale', ])
        ->add('isDefault', CheckboxType::class, [
            'empty_data' => '0',
            'label' => 'Default language?',
            'value' => 0,
            'required' => false, ])
        ->add('save', SubmitType::class, ['label' => 'Save', 'attr' => ['class' => 'btn btn-info btn-fill btn-wd update-profile-btn']]);
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
        return 'adminbundle_language';
    }
}
