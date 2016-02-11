<?php

namespace we\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AddTagType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('photo_id', null, array('label' => 'ID of Photo'))
            ->add('value', null, array('label' => 'Value of Tag'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'we\ClientBundle\Entity\Tag'
        ));
    }

    public function getName()
    {
        return 'tag';
    }
}
