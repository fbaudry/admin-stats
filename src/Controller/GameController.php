<?php

namespace App\Controller;

use App\Entity\League;
use App\Entity\Team;
use App\Helper\LeagueHelper;
use App\Helper\TeamHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
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
     * @ParamConverter("awayTeam", class=Team::class, name="awayTeam", converter="team")
     * @ParamConverter("homeTeam", class=Team::class, name="homeTeam", converter="team")
     * @Route("/league/{league}/game/{homeTeam}/{awayTeam}", name="app_game_index")
     */
    public function __invoke(League $league, Team $homeTeam, Team $awayTeam): Response
    {
        return $this->render('game.html.twig', [
            'away' => $this->getTeamInfo($league, $awayTeam),
            'awayTeam' => $awayTeam,
            'home' => $this->getTeamInfo($league, $homeTeam),
            'homeTeam' => $homeTeam
        ]);
    }

    private function getTeamInfo(League $league, Team $team): array
    {
        /* @var TeamHelper $teamHelper */
        $teamHelper = $this->container->get(TeamHelper::class);

        return [
            'teamResults' => $teamHelper->getTeamResults($team),
            'nbGamesWithoutNulPerYear' => $teamHelper->getNbGamesWithoutNulPerYear($league, $team),
            'nbNulGamesPerYear' => $teamHelper->getNbNulgamesPerYear($league, $team),
        ];
    }
}
