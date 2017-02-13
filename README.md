# FbeenContactformBundle

This Bundle adds contactform integration in your Symfony project. It lets you render and process contactforms and add them to the database. Includes a Sonata admin class to view the contact requests in the admin panel

### Features include:

* Doctrine ORM database storage of your contact requests
* Bootstrap ready pages and forms
* Sonata admin integration
* Login with just their mailaddress and password
* email admins and/or user a confirmation
* Use your own ContactRequest entity
* Use your own ContactRequest form type


## Installation

Using composer:

1) Add `"fbeen/contactformbundle": "dev-master"` to the require section of your composer.json project file.

```
    "require": {
        ...
        "fbeen/contactformbundle": "dev-master"
    },
```

2) run composer update:

    $ composer update

3) Add the bundles to the app/AppKernel.php:
```
        $bundles = array(
            ...
            new Fbeen\MailerBundle\FbeenMailerBundle(),
            new Fbeen\ContactformBundle\FbeenContactformBundle(),
        );
```
4) add routes to app/config/routing.yml
```
fbeen_user:
    resource: "@FbeenContactformBundle/Resources/config/routing.yml"
    prefix:   /contact
```

5) Enable Translation in `app/config/config.yml`
```
parameters:
    locale: en

framework:
    translator:      { fallbacks: ["%locale%"] }
```
6) Update your database schema
```
$ bin/console doctrine:schema:update --force
```
7) [Optional] Add minimal configuration for the FbeenContactformBundle in `app/config/config.yml`
This are all the configuration parameters with their defaultvalue:
```
fbeen_contactform:
    base_template: "FbeenContactformBundle::base.html.twig"
    redirect_after_submit: fbeen_contactform_confirmation
    contact_request_entity: Fbeen\ContactformBundle\Entity\ContactRequest
    contact_form_type: Fbeen\ContactformBundle\Form\ContactRequestType
    email_users: true
    email_users: false
```
8) [Optional] Use your own ContactRequest Entity
That is as simple as making any entity. There are only two things 
* Your entity must implement `Fbeen\ContactformBundle\Model\ContactRequestInterface`
```
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Fbeen\ContactformBundle\Model\ContactRequestInterface;

/**
 * ContactRequest
 *
 * @ORM\Table(name="fbeen_contact_request")
 * @ORM\Entity
 */
class ContactRequest implements ContactRequestInterface
{
    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;
    
    // add more properties
    
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return ContactRequest
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }
    
    // add more methods
}

```
* You must add the FQDN to the configuration:
```
fbeen_contactform:
    contact_request_entity: AppBundle\Entity\ContactRequest
```
9) [Optional] Use your own formType
That is as simple as making any formType and add configuration
```
<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactRequestType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array(
                'label' => 'form.email',
                'required' => true
            ))
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
```
* You must add the FQDN to the configuration:
```
fbeen_contactform:
    contact_form_type: AppBundle\Form\ContactRequestType
```