<?php

namespace App\Entity;

class League
{
    private $name;
    private $link;
    private $teams;
    private $ranking;
    private $lastGames;
    private $nextGames;
    private $gameWithoutNulRanking;
    private $slug;

    public function __construct(string $name, string $slug, string $link)
    {
        $this->name = $name;
        $this->link = $link;
        $this->teams = [];
        $this->ranking = [];
        $this->slug = $slug;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLink(): string
    {
        return $this->link;
    }

    public function setTeams(array $teams): self
    {
        $this->teams = $teams;

        return $this;
    }

    /**
     * @return Team[]
     */
    public function getTeams(): array
    {
        return $this->teams;
    }

    /**
     * @return
     */
    public function getRanking(): array
    {
        return $this->ranking;
    }

    public function setRanking(array $teams): self
    {
        $this->ranking = $teams;

        return $this;
    }

    /**
     * @return Game[]
     */
    public function getLastGames(): array
    {
        return $this->lastGames;
    }

    public function setLastGames(array $games): self
    {
        $this->lastGames = $games;

        return $this;
    }

    /**
     * @return Game[]
     */
    public function getNextGames(): array
    {
        return $this->nextGames;
    }

    public function setNextGames(array $games): self
    {
        $this->nextGames = $games;

        return $this;
    }

    /**
     * @return TeamRanking[]
     */
    public function getGameWithoutNulRanking(): array
    {
        return $this->gameWithoutNulRanking;
    }

    public function setGameWithoutNulRanking(array $games): self
    {
        $this->gameWithoutNulRanking = $games;

        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }
}
