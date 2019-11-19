<?php

namespace App\Helper;

use App\Entity\Game;
use App\Entity\League;
use App\Entity\Team;
use App\Entity\TeamRanking;
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

    public function getTeamWithTheMostNulGames(League $league): array
    {
        $lastGames = JsonParser::get(sprintf('public/json/leagues/%s/results.json', $league->getName()));

        $sortTeams = [];

        foreach ($lastGames as $game) {
            if(!isset($sortTeams[$game['home_team_name']])) { $sortTeams[$game['home_team_name']] = 0; }
            if(!isset($sortTeams[$game['away_team_name']])) { $sortTeams[$game['away_team_name']] = 0; }

            if($game['home_team_score'] !== $game['away_team_score']) {
                continue;
            }

            ++$sortTeams[$game['home_team_name']];
            ++$sortTeams[$game['away_team_name']];
        }

        arsort($sortTeams);

        $teamRankings = [];
        foreach ($sortTeams as $team => $nbPoints) {
            $teamRankings[] = new TeamRanking($team, $nbPoints);
        }

        return $teamRankings;
    }

    public function getHomeTeamWithTheMostNulGames(League $league): array
    {
        $lastGames = JsonParser::get(sprintf('public/json/leagues/%s/results.json', $league->getName()));

        $sortTeams = [];

        foreach ($lastGames as $game) {
            if(!isset($sortTeams[$game['home_team_name']])) { $sortTeams[$game['home_team_name']] = 0; }

            if($game['home_team_score'] !== $game['away_team_score']) {
                continue;
            }

            ++$sortTeams[$game['home_team_name']];
        }

        arsort($sortTeams);

        $teamRankings = [];
        foreach ($sortTeams as $team => $nbPoints) {
            $teamRankings[] = new TeamRanking($team, $nbPoints);
        }

        return $teamRankings;
    }

    public function getAwayTeamWithTheMostNulGames(League $league): array
    {
        $lastGames = JsonParser::get(sprintf('public/json/leagues/%s/results.json', $league->getName()));

        $sortTeams = [];

        foreach ($lastGames as $game) {
            if(!isset($sortTeams[$game['away_team_name']])) { $sortTeams[$game['away_team_name']] = 0; }

            if($game['home_team_score'] !== $game['away_team_score']) {
                continue;
            }

            ++$sortTeams[$game['away_team_name']];
        }

        arsort($sortTeams);

        $teamRankings = [];
        foreach ($sortTeams as $team => $nbPoints) {
            $teamRankings[] = new TeamRanking($team, $nbPoints);
        }

        return $teamRankings;
    }

    public function getTeamResults(Team $team): array
    {
        $results = JsonParser::get(sprintf('public/json/leagues/%s/results.json', $team->getLeague()->getName()));

        $teamResults = [
            'won' => 0,
            'nul' => 0,
            'lost' => 0
        ];

        $teamName = $team->getName();
        foreach ($results as $game) {
            if($game['away_team_name'] !== $teamName && $game['home_team_name'] !== $teamName) {
                continue;
            }

            if($game['home_team_score'] > $game['away_team_score']) {
                if($game['home_team_name'] === $teamName) {
                    ++$teamResults['won'];
                    continue;
                }

                ++$teamResults['lost'];
                continue;
            }

            if($game['home_team_score'] === $game['away_team_score']) {
                ++$teamResults['nul'];
                continue;
            }

            if($game['home_team_score'] < $game['away_team_score']) {
                if($game['home_team_name'] === $teamName) {
                    ++$teamResults['lost'];
                    continue;
                }

                ++$teamResults['won'];
                continue;
            }
        }

        return $teamResults;
    }

    public function getHomeTeamResults(Team $team): array
    {
        $results = JsonParser::get(sprintf('public/json/leagues/%s/results.json', $team->getLeague()->getName()));

        $teamResults = [
            'won' => 0,
            'nul' => 0,
            'lost' => 0
        ];

        $teamName = $team->getName();
        foreach ($results as $game) {
            if($game['home_team_name'] !== $teamName) {
                continue;
            }

            if($game['home_team_score'] > $game['away_team_score']) {
                ++$teamResults['won'];
                continue;
            }

            if($game['home_team_score'] === $game['away_team_score']) {
                ++$teamResults['nul'];
                continue;
            }

            if($game['home_team_score'] < $game['away_team_score']) {
                ++$teamResults['lost'];
                continue;
            }
        }

        return $teamResults;
    }

    public function getAwayTeamResults(Team $team): array
    {
        $results = JsonParser::get(sprintf('public/json/leagues/%s/results.json', $team->getLeague()->getName()));

        $teamResults = [
            'won' => 0,
            'nul' => 0,
            'lost' => 0
        ];

        $teamName = $team->getName();
        foreach ($results as $game) {
            if($game['away_team_name'] !== $teamName) {
                continue;
            }

            if($game['home_team_score'] > $game['away_team_score']) {
                ++$teamResults['lost'];
                continue;
            }

            if($game['home_team_score'] === $game['away_team_score']) {
                ++$teamResults['nul'];
                continue;
            }

            if($game['home_team_score'] < $game['away_team_score']) {
                ++$teamResults['won'];
                continue;
            }
        }

        return $teamResults;
    }

    public function getRecordsPerYear(League $league, Team $team)
    {
        $seasons = JsonParser::getFolder(\sprintf('public/json/leagues/%s/archives', $league->getName()));

        $records = [];
        $counters = [];

        foreach ($seasons as $season) {
            $records[$season] = []; $records[$season][$team->getName()] = 0;
            $counters[$season] = []; $counters[$season][$team->getName()] = 0;

            $games = JsonParser::get(\sprintf('public/json/leagues/%s/archives/%s/games.json', $league->getName(), $season));
            $days = array_keys($games);
            foreach ($days as $day) {
                $gamesOfTheDay = $games[$day];

                foreach ($gamesOfTheDay as $game) {

                    if($game['home_team_name'] === $team->getName()) {
                        if(!isset($counters[$season][$game['home_team_name']])) { $counters[$season][$game['home_team_name']] = 0; };

                        if($game['home_team_score'] !== $game['away_team_score']) {
                            ++$counters[$season][$game['home_team_name']];
                            if($counters[$season][$game['home_team_name']] > $records[$season][$game['home_team_name']]) { $records[$season][$game['home_team_name']] = $counters[$season][$game['home_team_name']]; }

                            continue;
                        }

                        $counters[$season][$game['home_team_name']] = 0;
                    }

                    if($game['away_team_name'] === $team->getName()) {
                        if(!isset($counters[$season][$game['away_team_name']])) { $counters[$season][$game['away_team_name']] = 0; };

                        if($game['home_team_score'] !== $game['away_team_score']) {
                            ++$counters[$season][$game['away_team_name']];
                            if($counters[$season][$game['away_team_name']] > $records[$season][$game['away_team_name']]) { $records[$season][$game['away_team_name']] = $counters[$season][$game['away_team_name']]; }

                            continue;
                        }

                        $counters[$season][$game['home_team_name']] = 0;
                    }

                }
            }
        }

        return $records;
    }
}
