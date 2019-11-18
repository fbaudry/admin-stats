<?php

namespace App\Entity;

class Team
{
    private $league;
    private $name;

    public function __construct(League $league, string $name)
    {
        $this->league = $league;
        $this->name = $name;
    }

    public function getLeague(): League
    {
        return $this->league;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
