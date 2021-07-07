<?php

namespace HitcKit\AdminBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * @inheritDoc
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('hitc_kit_admin');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('menu_user')
                            ->cannotBeEmpty()
                            ->defaultValue('@HitcKitAdmin/menu_user.html.twig')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('menu')
                    ->addDefaultsIfNotSet()
                    ->fixXmlConfig('item')
                    ->append($this->getConfigAttributesRoot())
                    ->append($this->getConfigRender())
                    ->append($this->getConfigItems('items'))
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }

    private function getConfigItems($name)
    {
        $treeBuilder = new TreeBuilder($name);

        return $treeBuilder->getRootNode()
            ->defaultValue([])
            ->useAttributeAsKey('name')
            ->arrayPrototype()
                ->ignoreExtraKeys(false)
                ->children()
                    ->booleanNode('display')->end()
                    ->arrayNode('children')
                        ->ignoreExtraKeys(false)
                        ->validate()->ifEmpty()->thenUnset()->end()
                    ->end()
                    ->arrayNode('extras')
                        ->ignoreExtraKeys()
                        ->fixXmlConfig('route_inject_param')
                        ->children()
                            ->scalarNode('icon_class')->end()
                            ->scalarNode('translation_domain')->end()
                            ->arrayNode('translation_params')->ignoreExtraKeys()->end()
                            ->booleanNode('safe_label')->end()
                            ->arrayNode('route_inject_params')->scalarPrototype()->end()->end()
                        ->end()
                    ->end()
                    ->scalarNode('uri')->end()
                    ->scalarNode('route')->end()
                    ->arrayNode('routeParameters')->ignoreExtraKeys()->end()
                ->end()
            ->end()
        ;
    }

    private function getConfigRender()
    {
        $treeBuilder = new TreeBuilder('render');

        return $treeBuilder->getRootNode()
            ->ignoreExtraKeys(false)
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('template')->defaultValue('@HitcKitAdmin/menu.html.twig')->end()
                ->integerNode('depth')->min(0)->end()
                ->integerNode('matchingDepth')->min(0)->end()
                ->booleanNode('currentAsLink')->end()
                ->scalarNode('currentClass')->end()
                ->scalarNode('ancestorClass')->end()
                ->scalarNode('firstClass')->end()
                ->booleanNode('compressed')->end()
                ->booleanNode('allow_safe_labels')->end()
                ->booleanNode('clear_matcher')->end()
                ->scalarNode('leaf_class')->defaultValue('nav-item')->end()
                ->scalarNode('branch_class')->end()
                ->scalarNode('list_class')
                    ->validate()->ifEmpty()->thenUnset()->end()
                    ->defaultValue('nav nav-treeview')
                ->end()
                ->scalarNode('item_class')
                    ->validate()->ifEmpty()->thenUnset()->end()
                    ->defaultValue('nav-item')
                ->end()
                ->scalarNode('link_class')
                    ->validate()->ifEmpty()->thenUnset()->end()
                    ->defaultValue('nav-link')
                ->end()
                ->scalarNode('link_current_class')
                    ->validate()->ifEmpty()->thenUnset()->end()
                    ->defaultValue('active')
                ->end()
                ->scalarNode('link_ancestor_class')
                    ->validate()->ifEmpty()->thenUnset()->end()
                    ->defaultValue('active')
                ->end()
            ->end()
        ;
    }

    private function getConfigAttributesRoot()
    {
        $treeBuilder = new TreeBuilder('root_attributes');

        return $treeBuilder->getRootNode()
            ->ignoreExtraKeys(false)
            ->addDefaultsIfNotSet()
            ->children()
                ->scalarNode('class')
                    ->validate()->ifEmpty()->thenUnset()->end()
                    ->defaultValue('nav nav-pills nav-sidebar flex-column nav-flat')
                ->end()
                ->scalarNode('data-widget')
                    ->validate()->ifEmpty()->thenUnset()->end()
                    ->defaultValue('treeview')
                ->end()
                ->scalarNode('role')
                    ->validate()->ifEmpty()->thenUnset()->end()
                    ->defaultValue('menu')
                ->end()
                ->scalarNode('data-accordion')
                    ->validate()->ifEmpty()->thenUnset()->end()
                    ->defaultValue('false')
                ->end()
            ->end()
        ;
    }
}
