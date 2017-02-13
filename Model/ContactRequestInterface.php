<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Fbeen\ContactformBundle\Model;

/**
 * ContactRequest interface that you could implement into your own ContactRequest class
 * 
 * @link https://github.com/Fbeen/ContactformBundle
 * 
 * @author Frank Beentjes <frankbeen@gmail.com>
 */
interface ContactRequestInterface
{
    /**
     * Set email
     *
     * @param string $email
     *
     * @return ContactRequest
     */
    public function setEmail($email);

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail();
}
