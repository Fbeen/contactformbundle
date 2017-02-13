<?php

namespace Fbeen\ContactformBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/configuration.html}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('fbeen_contactform');
        
        $rootNode
            ->children()
                ->scalarNode('base_template')
                    ->defaultValue('FbeenContactformBundle::base.html.twig')
                ->end()
                ->scalarNode('redirect_after_submit')
                    ->defaultValue('fbeen_contactform_confirmation')
                ->end()
                ->scalarNode('contact_request_entity')
                    ->defaultValue('Fbeen\ContactformBundle\Entity\ContactRequest')
                ->end()
                ->scalarNode('contact_form_type')
                    ->defaultValue('Fbeen\ContactformBundle\Form\ContactRequestType')
                ->end()
                ->booleanNode('email_admins')
                    ->defaultTrue()
                ->end()
                ->booleanNode('email_users')
                    ->defaultFalse()
                ->end()
            ->end()
        ;
        
        return $treeBuilder;
    }
}
