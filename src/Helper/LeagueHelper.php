<?php

namespace App\Helper;

use App\Entity\League;
use App\Entity\Team;
use App\Entity\TeamRanking;
use App\Parser\JsonParser;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class LeagueHelper implements ServiceSubscriberInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function getSubscribedServices()
    {
        return [
            GameHelper::class,
            JsonParser::class,
            TeamHelper::class
        ];
    }

    /**
     * @return League[]
     */
    public function getLeagues(): array
    {
        /* @var GameHelper $gameHelper */
        $gameHelper = $this->container->get(GameHelper::class);
        /* @var TeamHelper $teamHelper */
        $teamHelper = $this->container->get(TeamHelper::class);

        $leagueDatas = JsonParser::get('public/json/leagues/leagues.json');
        $leagues = [];

        foreach ($leagueDatas as $data) {
            $league = new League($data['name'], $data['link']);
            $league
                ->setRanking($this->getRanking($league))
                ->setLastGames($gameHelper->getLastGames($league))
                ->setNextGames($gameHelper->getNextGames($league))
                ->setTeams($teamHelper->getTeams($league))
                ->setGameWithoutNulRanking($this->getGameWithoutNulRanking($league))
            ;

            $leagues[] = $league;
        }

        return $leagues;
    }

    public function getRanking(League $league): array
    {
        $rankingDatas = JsonParser::get(sprintf('public/json/leagues/%s/classement.json', $league->getName()));
        $ranking = [];
        foreach ($rankingDatas as $key => $data) {
            $ranking[] = new TeamRanking($data['team_name'], $data['nb_points']);
        }

        return $ranking;
    }

    public function getGameWithoutNulRanking(League $league): array
    {
        $rankingDatas = JsonParser::get(sprintf('public/json/leagues/%s/game_without_nul.json', $league->getName()));
        $ranking = [];
        foreach ($rankingDatas as $key => $data) {
            $ranking[] = new TeamRanking($data['team_name'], $data['nb_points']);
        }

        return $ranking;
    }
}
