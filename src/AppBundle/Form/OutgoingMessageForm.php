<?php

namespace AppBundle\Form;

use AppBundle\Entity\Conversation;
use AppBundle\Form\DataTransformer\UserConversationToConversation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OutgoingMessageForm extends AbstractType
{

    private $transformer;

    public function __construct(UserConversationToConversation $transformer)
    {
        $this->transformer = $transformer;
    }


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('conversation',EntityType::class, [
                'class' => Conversation::class
            ])
            ->add('messageBody');

        $builder->get('conversation')
            ->addModelTransformer($this->transformer);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\OutgoingMessage',
            'allow_extra_fields' => true,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_reply';
    }
}
