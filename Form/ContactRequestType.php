<?php

namespace Fbeen\ContactformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ContactRequestType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label' => 'form.name'
            ))
            ->add('telephone', null, array(
                'label' => 'form.telephone'
            ))
            ->add('email', EmailType::class, array(
                'label' => 'form.email',
                'required' => true
            ))
            ->add('message', null, array(
                'label' => 'form.message',
                'attr' => array(
                    'rows' => 10
                )))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Fbeen\ContactformBundle\Entity\ContactRequest',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'fbeen_contactformbundle_contactrequest';
    }


}
