<?php

namespace Fbeen\ContactformBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class ContactRequestAdmin extends AbstractAdmin
{
    protected $datagridValues = array(

        // display the first page (default = 1)
        '_page' => 1,

        // reverse order (default = 'ASC')
        '_sort_order' => 'DESC',

        // name of the ordered field (default = the model's id field, if any)
        '_sort_by' => 'created',
    );

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('name', null, array('label' => 'form.name'))
            ->add('telephone', null, array('label' => 'form.telephone'))
            ->add('email', null, array('label' => 'form.email'))
            ->add('answered', null, array('label' => 'form.answered'))
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('name', null, array('label' => 'form.name'))
            ->add('email', null, array('label' => 'form.email'))
            ->add('created', null, array(
                'label' => 'form.created',
                'format' => 'd-m-Y H:i'
            ))
            ->add('answered', null, array(
                'label' => 'form.answered',
                'editable' => true
            ))
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('name', null, array('label' => 'form.name'))
            ->add('telephone', null, array('label' => 'form.telephone'))
            ->add('email', null, array('label' => 'form.email'))
            ->add('message', null, array('label' => 'form.message'))
            ->add('answered', null, array('label' => 'form.answered'))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name', null, array('label' => 'form.name'))
            ->add('telephone', null, array('label' => 'form.telephone'))
            ->add('email', 'email', array('label' => 'form.email'))
            ->add('message', null, array('label' => 'form.message'))
            ->add('created', null, array(
                'label' => 'form.created',
                'format' => 'd-m-Y H:i'
            ))
            ->add('answered', null, array('label' => 'form.answered'))
            ->add('answeredAt', null, array(
                'label' => 'form.answered_at',
                'format' => 'd-m-Y H:i'
            ))
        ;
    }
    
    protected function configureRoutes(RouteCollection $collection)
    {
        // to remove a single route
        $collection->remove('create');
        $collection->remove('edit');
    }
    
    public function preUpdate($object)
    {
        if($object->getAnsweredAt() === null && $object->getAnswered() === true)
        {
            $object->setAnsweredAt(new \DateTime());
        }
    }
}
