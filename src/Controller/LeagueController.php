<?php

namespace App\Controller;

use App\Entity\League;
use App\Helper\LeagueHelper;
use App\Helper\TeamHelper;
use App\Parser\JsonParser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;


class LeagueController extends AbstractController
{
    public static function getSubscribedServices()
    {
        return \array_merge(parent::getSubscribedServices(), [
            LeagueHelper::class,
            TeamHelper::class
        ]);
    }

    /**
     * @ParamConverter("league", class=League::class, name="league", converter="league")
     * @Route("/league/{league}", name="app_league_index")
     */
    public function __invoke(League $league): Response
    {
        /* @var TeamHelper $teamHelper */
        $teamHelper = $this->container->get(TeamHelper::class);

        return $this->render('league.html.twig', [
            'league' => $league,
            'teamWithMostNulGames' => $teamHelper->getTeamWithTheMostNulGames($league),
            'homeTeamWithMostNulGames' => $teamHelper->getHomeTeamWithTheMostNulGames($league),
            'awayTeamWithMostNulGames' => $teamHelper->getAwayTeamWithTheMostNulGames($league)
        ]);
    }
}
