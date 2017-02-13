<?php

namespace Fbeen\ContactformBundle\Entity;

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
    use \Fbeen\ContactformBundle\Model\ContactRequestTrait;
}

