<?php

namespace App\Helper;

use App\Entity\Game;
use App\Entity\League;
use App\Entity\Team;
use App\Parser\JsonParser;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class GameHelper implements ServiceSubscriberInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function getSubscribedServices()
    {
        return [
            JsonParser::class
        ];
    }

    /**
     * @return Game
     */
    public function getLastGames(League $league): array
    {
        $gameDatas = JsonParser::get(sprintf('public/json/leagues/%s/last_games.json', $league->getName()));
        $games = [];
        foreach ($gameDatas as $data) {
            $games[] = (new Game($data['home_team_name'], $data['away_team_name']))
                ->setAwayTeamScore($data['away_team_score'])
                ->setHomeTeamScore($data['home_team_score'])
            ;
        }

        return $games;
    }

    /**
     * @return Game[]
     */
    public function getNextGames(League $league): array
    {
        $gameDatas = JsonParser::get(sprintf('public/json/leagues/%s/next_games.json', $league->getName()));
        $games = [];
        foreach ($gameDatas as $data) {
            $games[] = (new Game($data['home_team_name'], $data['away_team_name']));
        }

        return $games;
    }
}
