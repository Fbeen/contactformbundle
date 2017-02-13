<?php

namespace Fbeen\ContactformBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\Exception\BadMethodCallException;
use Fbeen\ContactformBundle\Form\ContactRequestType;
use Fbeen\ContactformBundle\Model\ContactRequestInterface;

class ContactController extends Controller
{
    public function formAction(Request $request)
    {
        $entity = $this->determineEntity();
        $contactRequest = new $entity();

        $form = $this->createContactform($contactRequest);

        return $this->render('FbeenContactformBundle:Contact:index.html.twig', array(
            'base_template' => $this->getParameter('fbeen_contactform.base_template'),
            'form' => $form->createView(),
        ));
    }
    
    public function updateAction(Request $request)
    {
        $entity = $this->determineEntity();
        $contactRequest = new $entity();

        $form = $this->createContactform($contactRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($contactRequest);
            $em->flush();
            
            /* set id of contactrequest entity in the session for use on the confirmation */
            $request->getSession()->set('contact_request_id', $contactRequest->getId());
            
            /* email admin and/or user depending on the settings */
            if($this->getParameter('fbeen_contactform.email_admins'))
            {
                $this->emailAdmins($contactRequest);
            }
            if($this->getParameter('fbeen_contactform.email_users'))
            {
                $this->emailUser($contactRequest);
            }

            return $this->redirectToRoute($this->getParameter('fbeen_contactform.redirect_after_submit'));
        }

        return $this->render('FbeenContactformBundle:Contact:update.html.twig', array(
            'base_template' => $this->getParameter('fbeen_contactform.base_template'),
            'form' => $form->createView(),
        ));
    }
    
    public function confirmationAction(Request $request)
    {
        $entity = $this->determineEntity();
        $em = $this->getDoctrine()->getManager();
        $contactRequest = null;
        
        $id = $request->getSession()->get('contact_request_id');
        
        if($id > 0)
        {
            $contactRequest = $em->getRepository($entity)->find($id);
        }
        
        return $this->render('FbeenContactformBundle:Contact:confirmation.html.twig', array(
            'base_template' => $this->getParameter('fbeen_contactform.base_template'),
            'contactRequest' => $contactRequest
        ));
    }
    
    /*
     * read from configuration which ContactRequest entity should be used and checks if that entity implements ContactRequestInterface
     */
    private function determineEntity()
    {
        $entity = $this->getParameter('fbeen_contactform.contact_request_entity');
        
        if(!in_array('Fbeen\ContactformBundle\Model\ContactRequestInterface', class_implements($entity)))
        {
            throw new BadMethodCallException('Your ContactRequest entity must implement Fbeen\ContactformBundle\Model\ContactRequestInterface');
        }
        
        return $entity;
    }

    private function createContactform(ContactRequestInterface $contactRequest)
    {
        return $this->createForm($this->getParameter('fbeen_contactform.contact_form_type'), $contactRequest, array(
            'data_class' => get_class($contactRequest),
            'action' => $this->generateUrl('fbeen_contactform_update'),
            'method' => 'post'
        ));
    }

    private function emailAdmins(ContactRequestInterface $contactRequest)
    {
        $this->get('fbeen_mailer')
           ->setSubject($this->get('translator')->trans('email_admins.subject'))
           ->setTemplate('FbeenContactformBundle:Email:confirm_admins.html.twig')
           ->setData(array(
               'contactRequest' => $contactRequest
            ))
           ->sendMail()
        ;
    }
    
    private function emailUser(ContactRequestInterface $contactRequest)
    {
        $this->get('fbeen_mailer')
           ->setTo($contactRequest->getEmail())
           ->setSubject($this->get('translator')->trans('email_user.subject'))
           ->setTemplate('FbeenContactformBundle:Email:confirm_user.html.twig')
           ->setData(array(
               'contactRequest' => $contactRequest
            ))
           ->sendMail()
        ;
    }
}
