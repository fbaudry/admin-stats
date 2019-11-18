<?php

namespace App\Controller;

use App\Helper\LeagueHelper;
use App\Helper\NavigationHelper;
use App\Parser\JsonParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

class NavigationController extends AbstractController
{
    public static function getSubscribedServices()
    {
        return \array_merge(parent::getSubscribedServices(), [
            NavigationHelper::class
        ]);
    }

    public function __invoke(): Response
    {
        return $this->render('navigation.html.twig', [
            'nav' => $this->get(NavigationHelper::class)->getNavigation()
        ]);
    }
}
