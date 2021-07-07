<?php

namespace HitcKit\AdminBundle\Services;

use Knp\Menu\ItemInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\RuntimeExtensionInterface;

class AdminTwigRuntime implements RuntimeExtensionInterface
{
    private $request;
    private $generator;

    public function __construct(RequestStack $request, UrlGeneratorInterface $generator)
    {
        $this->request = $request->getCurrentRequest();
        $this->generator = $generator;
    }

    public function adminPath(ItemInterface $item, array $parameters = [], bool $relative = false): string
    {
        $pathInfo = $item->getExtra('routes', [false])[0];

        if ($pathInfo) {
            $source = array_diff($this->request->attributes->get('_route_params'), ['']);
            $intersect = array_fill_keys($item->getExtra('route_inject_params', []), null);
            $parameters = array_merge($pathInfo['parameters'], array_intersect_key($source, $intersect), $parameters);
            $referenceType = $relative ? UrlGeneratorInterface::RELATIVE_PATH : UrlGeneratorInterface::ABSOLUTE_PATH;

            return $this->generator->generate($pathInfo['route'], $parameters, $referenceType);
        } else {
            return $item->getUri();
        }
    }
}
