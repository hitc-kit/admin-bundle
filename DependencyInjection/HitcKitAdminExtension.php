<?php

namespace HitcKit\AdminBundle\DependencyInjection;

use Exception;
// use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;
use HitcKit\AdminBundle\Services\AdminMenuProvider;

class HitcKitAdminExtension extends ConfigurableExtension implements PrependExtensionInterface
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function loadInternal(array $config, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $this->childrenValidate($config);

        $definition = $container->getDefinition(AdminMenuProvider::class);
        $definition->addMethodCall('setConfig', [$config['menu']]);

        $container->setParameter('hitc_kit_admin.templates.menu_user', $config['templates']['menu_user']);
    }

    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['WebpackEncoreBundle'])) {
            $container->prependExtensionConfig('webpack_encore', [
                'builds' => [
                    'admin' => '%kernel.project_dir%/public/bundles/hitckitadmin/build'
                ]
            ]);
        }
    }

    private function childrenValidate(array $config)
    {
        $validate = ['menu' => ['items' => $this->childrenExtract($config['menu']['items'])]];
        $this->processConfiguration(new Configuration(), [$validate]);
    }

    private function childrenExtract(array $items, $nameParent = '')
    {
        $extracted = [];

        foreach ($items as $name => $item) {
            $children = $item['children'] ?? false;
            unset($item['children']);

            if (empty($nameParent)) {
                $key = $name;
            } else {
                $key = $nameParent.'.'.$name;
                $extracted[$key] = $item;
            }

            if ($children) {
                $extracted = array_merge($extracted, $this->childrenExtract($children, $key));
            }
        }

        return $extracted;
    }
}
