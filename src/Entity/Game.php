<?php

namespace App\Entity;

class Game
{
    private $homeTeamName;
    private $awayTeamName;
    private $homeTeamScore;
    private $awayTeamScore;

    public function __construct(string $homeTeamName, string $awayTeamName)
    {
        $this->homeTeamName = $homeTeamName;
        $this->awayTeamName = $awayTeamName;
    }

    public function getHomeTeamName(): string
    {
        return $this->homeTeamName;
    }

    public function getAwayTeamName(): string
    {
        return $this->awayTeamName;
    }

    public function setHomeTeamScore(int $homeTeamScore): self
    {
        $this->homeTeamScore = $homeTeamScore;

        return $this;
    }

    public function getHomeTeamScore(): ?int
    {
        return $this->homeTeamScore;
    }

    public function setAwayTeamScore(int $awayTeamScore): self
    {
        $this->awayTeamScore = $awayTeamScore;

        return $this;
    }

    public function getAwayTeamScore(): ?int
    {
        return $this->awayTeamScore;
    }
}
