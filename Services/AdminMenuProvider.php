<?php

namespace HitcKit\AdminBundle\Services;

use InvalidArgumentException;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Knp\Menu\Provider\MenuProviderInterface;

class AdminMenuProvider implements MenuProviderInterface
{
    private $factory;
    private $tree;
    private $config;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function get(string $name = 'admin_tree', array $options = []): ItemInterface
    {
        if ('admin_tree' !== $name) {
            throw new InvalidArgumentException(sprintf('The menu "%s" is not defined.', $name));
        }

        if (!isset($this->tree)) {
            $this->tree = $this->factory->createItem('root', [
                'route' => 'hitc_kit_admin_dashboard',
                'extras' => ['translation_domain' => 'HitcKitAdminBundle', 'render_options' => $this->config['render']],
                'childrenAttributes' => $this->config['root_attributes']
            ]);

            $this->setChildren($this->tree, $this->config['items']);
        }

        return $this->tree;
    }

    public function has(string $name, array $options = []): bool
    {
        return 'admin_tree' == $name;
    }

    public function setConfig(array $config): self
    {
        $this->config = $config;

        return $this;
    }

    private function setChildren(ItemInterface $parent, array $items)
    {
        $merge = array_intersect([
            'childrenAttributes' => 'list_class',
            'attributes' => 'item_class',
            'linkAttributes' => 'link_class'
        ], array_keys($this->config['render']));

        foreach ($items as $key => $options) {
            foreach ($merge as $target => $source) {
                $options[$target]['class'] = isset($options[$target]['class'])
                    ? $options[$target]['class'].' '.$this->config['render'][$source]
                    : $this->config['render'][$source]
                ;
            }

            if (isset($options['children'])) {
                $children = $options['children'];
                unset($options['children']);
            } else {
                $children = false;
            }

            $child = $parent->addChild($key, $options);

            if ($children) {
                $this->setChildren($child, $children);
            }
        }
    }
}
