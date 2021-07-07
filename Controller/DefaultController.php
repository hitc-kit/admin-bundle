<?php

namespace HitcKit\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("{zero}", defaults={"zero": ""}, requirements={"zero": "^\\/?$"}, name="hitc_kit_admin_dashboard")
     * @return Response
     */
    public function dashboard(): Response
    {
        return $this->render('@HitcKitAdmin/dashboard.html.twig');
    }

    public function menuUser(): Response
    {
        return $this->render($this->getParameter('hitc_kit_admin.templates.menu_user'));
    }
}
