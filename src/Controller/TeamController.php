<?php

namespace App\Controller;

use App\Entity\League;
use App\Entity\Team;
use App\Helper\TeamHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TeamController extends AbstractController
{
    public static function getSubscribedServices()
    {
        return \array_merge(parent::getSubscribedServices(), [
            TeamHelper::class
        ]);
    }

    /**
     * @ParamConverter("league", class=League::class, name="league", converter="league")
     * @ParamConverter("team", class=Team::class, name="team", converter="team")
     * @Route("/{league}/{team}", name="app_team_show")
     */
    public function __invoke(League $league, Team $team): Response
    {
        /* @var TeamHelper $teamHelper */
        $teamHelper = $this->container->get(TeamHelper::class);

        return $this->render('team.html.twig', [
            'team' => $team,
            'teamResults' => $teamHelper->getTeamResults($team),
            'homeTeamResults' => $teamHelper->getHomeTeamResults($team),
            'awayTeamResults' => $teamHelper->getAwayTeamResults($team)
        ]);
    }
}
