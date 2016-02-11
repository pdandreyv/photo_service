<?php

namespace we\ClientBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use we\ClientBundle\Entity\Photo;
use Doctrine\ORM\EntityRepository;

class SendPhotoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tags', 'text', array('label' => 'Tags'))
            ->add('photo', 'file', array('label' => 'Photo','data_class' => null,'required' => true))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'we\ClientBundle\Entity\Photo'
        ));
    }

    public function getName()
    {
        return 'photo';
    }
}
