<?php

namespace Vidia\AdminBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TranslationConstantValueForm extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('value', TextType::class, [
            'attr' => ['class' => 'form-control border-input', 'placeholder' => 'Value'],
            'label' => 'Value', ])
            ->add('language', EntityType::class, [
                'class' => 'AppBundle:Language', 'choice_label' => 'name',
                'attr' => ['class' => 'form-control border-input'], ])
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
        return 'adminbundle_translation_constant_value';
    }
}
