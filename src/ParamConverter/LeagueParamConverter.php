<?php

namespace App\ParamConverter;

use App\Entity\League;
use App\Parser\JsonParser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class LeagueParamConverter implements ParamConverterInterface
{
    public function apply(Request $request, ParamConverter $configuration)
    {
        $leaguesData = JsonParser::get('public/json/leagues/leagues.json');
        $name = $request->attributes->get('league');

        foreach($leaguesData as $data) {
            if($name !== $data['slug']) {
                continue;
            }

            $request->attributes->set('league', new League($data['name'], $data['slug'], $data['link']));
        }

    }

    public function supports(ParamConverter $configuration)
    {
        return 'league' === $configuration->getName();
    }
}
