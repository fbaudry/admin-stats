<?php

namespace App\Helper;

use App\Entity\Game;
use App\Entity\League;
use App\Entity\Team;
use App\Parser\JsonParser;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class TeamHelper implements ServiceSubscriberInterface
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
     * @return Team[]
     */
    public function getTeams(League $league): array
    {
        $teamDatas = JsonParser::get(sprintf('public/json/leagues/%s/teams.json', $league->getName()));
        $teams = [];
        foreach ($teamDatas as $teamName) {
            $team[] = new Team($league, $teamName);
        }

        return $teams;
    }
}
