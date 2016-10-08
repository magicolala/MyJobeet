<?php

namespace Ens\JobeetBundle\Form;

use Ens\JobeetBundle\Entity\Job;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class JobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('category');
        $builder->add('type', 'choice', array('choices' => Job::getTypes(), 'expanded' => true));
        $builder->add('company');
        $builder->add('logo', null, array('label' => 'Company logo'));
        $builder->add('url');
        $builder->add('position');
        $builder->add('location');
        $builder->add('description');
        $builder->add('file', 'file', array('label' => 'Company logo', 'required' => false));
        $builder->add('howToApply', null, array('label' => 'How to apply?'));
        $builder->add('token');
        $builder->add('is_public', null, array('label' => 'Public?'));
        $builder->add('email');
    }

    public function getName()
    {
        return 'ens_jobeetbundle_jobtype';
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ens\JobeetBundle\Entity\Job'
        ));
    }
}
