<?php

namespace App\Controller;

use App\Helper\LeagueHelper;
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

class IndexController extends AbstractController
{
    public static function getSubscribedServices()
    {
        return \array_merge(parent::getSubscribedServices(), [
            LeagueHelper::class
        ]);
    }

    /**
     * @Route("/", name="app_index")
     */
    public function __invoke(): Response
    {
        $leagues = $this->get(LeagueHelper::class)->getLeagues();

        return $this->render('index.html.twig', [
            'leagues' => $leagues
        ]);
    }
}
