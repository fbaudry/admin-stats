<?php

namespace App\ParamConverter;

use App\Entity\League;
use App\Entity\Team;
use App\Helper\SlugHelper;
use App\Parser\JsonParser;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;

class TeamParamConverter implements ParamConverterInterface
{
    public function apply(Request $request, ParamConverter $configuration)
    {
        $league = $request->attributes->get('league');
        $teamSlug = $request->attributes->get('team');

        $teams = JsonParser::get(sprintf('public/json/leagues/%s/teams.json', $league->getName()));

        foreach($teams as $teamName) {
            if($teamSlug !== SlugHelper::slugify($teamName)) {
                continue;
            }

            $request->attributes->set('team', new Team($league, $teamName));
        }
    }

    public function supports(ParamConverter $configuration)
    {
        return 'team' === $configuration->getName();
    }
}
