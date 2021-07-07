<?php

namespace HitcKit\AdminBundle\Services;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AdminTwigExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('admin_path', [AdminTwigRuntime::class, 'adminPath']),
        ];
    }
}
