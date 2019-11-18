<?php

namespace App\Helper;

use App\Entity\League;
use App\Parser\JsonParser;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class NavigationHelper implements ServiceSubscriberInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function getSubscribedServices()
    {
        return [
        ];
    }

    public function getNavigation(): array
    {
        $navigation = [];

        $leagueDatas = JsonParser::get('public/json/leagues/leagues.json');

        foreach ($leagueDatas as $data) {
            if(!isset($navigation['leagues'])) {
                $navigation['leagues'] = [];
            }

            $navigation['leagues'][] = new League($data['name'], $data['slug'], $data['link']);
        }

        return $navigation;
    }
}
